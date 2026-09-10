<?php
require_once '../config/database.php';

$errors = [];
$success_message = '';
$editing_service = null;
$form_values = [
    'service_name' => '',
    'category' => '',
    'description' => '',
    'price' => '',
    'duration' => '',
];

function clean_value($value)
{
    return trim((string) $value);
}

function valid_service_id($value)
{
    return filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) !== false;
}

function validate_service_form($values)
{
    $validation_errors = [];

    if ($values['service_name'] === '') {
        $validation_errors[] = 'Service name is required.';
    }
    if ($values['category'] === '') {
        $validation_errors[] = 'Category is required.';
    }
    if ($values['description'] === '') {
        $validation_errors[] = 'Description is required.';
    }
    if ($values['price'] === '' || !is_numeric($values['price']) || (float) $values['price'] < 0) {
        $validation_errors[] = 'Price must be a number that is zero or greater.';
    }
    if ($values['duration'] === '' || !valid_service_id($values['duration'])) {
        $validation_errors[] = 'Duration must be a positive whole number of minutes.';
    }

    return $validation_errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $service_id = $_POST['id'] ?? '';

    if ($action === 'delete') {
        if (!valid_service_id($service_id)) {
            $errors[] = 'A valid service ID is required to delete a service.';
        } else {
            $delete_statement = $conn->prepare('DELETE FROM services WHERE id = ?');
            if ($delete_statement === false) {
                $errors[] = 'The service could not be deleted. Please try again.';
            } else {
                $delete_statement->bind_param('i', $service_id);
                if (!$delete_statement->execute()) {
                    $errors[] = 'The service could not be deleted. Please try again.';
                } elseif ($delete_statement->affected_rows === 0) {
                    $errors[] = 'The requested service could not be found.';
                } else {
                    $success_message = 'Service deleted successfully.';
                }
                $delete_statement->close();
            }
        }
    } elseif ($action === 'save') {
        $form_values = [
            'service_name' => clean_value($_POST['service_name'] ?? ''),
            'category' => clean_value($_POST['category'] ?? ''),
            'description' => clean_value($_POST['description'] ?? ''),
            'price' => clean_value($_POST['price'] ?? ''),
            'duration' => clean_value($_POST['duration'] ?? ''),
        ];
        $errors = validate_service_form($form_values);

        if (valid_service_id($service_id)) {
            $editing_service = (int) $service_id;
        } elseif ($service_id !== '') {
            $errors[] = 'The service ID is invalid.';
        }

        if (count($errors) === 0) {
            if ($editing_service !== null) {
                $statement = $conn->prepare('UPDATE services SET service_name = ?, category = ?, description = ?, price = ?, duration = ? WHERE id = ?');
                $success_message = 'Service updated successfully.';
            } else {
                $statement = $conn->prepare('INSERT INTO services (service_name, category, description, price, duration) VALUES (?, ?, ?, ?, ?)');
                $success_message = 'Service added successfully.';
            }

            if ($statement !== false) {
                if ($editing_service !== null) {
                    $statement->bind_param('sssdii', $form_values['service_name'], $form_values['category'], $form_values['description'], $form_values['price'], $form_values['duration'], $editing_service);
                } else {
                    $statement->bind_param('sssdi', $form_values['service_name'], $form_values['category'], $form_values['description'], $form_values['price'], $form_values['duration']);
                }
            }

            if ($statement === false || !$statement->execute()) {
                $errors[] = 'The service could not be saved. Please check the database and try again.';
                $success_message = '';
            }
            if ($statement !== false) {
                $statement->close();
            }
            if (count($errors) === 0) {
                $form_values = ['service_name' => '', 'category' => '', 'description' => '', 'price' => '', 'duration' => ''];
                $editing_service = null;
            }
        }
    }
}

if (isset($_GET['edit'])) {
    if (!valid_service_id($_GET['edit'])) {
        $errors[] = 'The requested service ID is invalid.';
    } else {
        $edit_id = (int) $_GET['edit'];
        $statement = $conn->prepare('SELECT service_name, category, description, price, duration FROM services WHERE id = ?');
        $statement->bind_param('i', $edit_id);
        $statement->execute();
        $statement->bind_result($service_name, $category, $description, $price, $duration);
        if ($statement->fetch()) {
            $editing_service = $edit_id;
            $form_values = ['service_name' => $service_name, 'category' => $category, 'description' => $description, 'price' => $price, 'duration' => $duration];
        } else {
            $errors[] = 'The requested service could not be found.';
        }
        $statement->close();
    }
}

$services = [];
$statement = $conn->prepare('SELECT id, service_name, category, description, price, duration FROM services ORDER BY id DESC');
if ($statement !== false && $statement->execute()) {
    $statement->bind_result($id, $service_name, $category, $description, $price, $duration);
    while ($statement->fetch()) {
        $services[] = ['id' => $id, 'service_name' => $service_name, 'category' => $category, 'description' => $description, 'price' => $price, 'duration' => $duration];
    }
    $statement->close();
} elseif ($statement !== false) {
    $statement->close();
    $errors[] = 'Services could not be loaded. Please try again.';
}

function admin_escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Services | The Glam Room</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/services.css">
</head>
<body class="admin-page">
    <header class="services-header"><nav class="navbar"><div class="container"><a class="navbar-brand" href="../index.php"><span class="brand-mark">G</span><span>The Glam <em>Room</em></span></a><a class="nav-link" href="../services.php">View public services ↗</a></div></nav></header>
    <main class="admin-main"><div class="container"><p class="eyebrow">Management area</p><h1>Manage <em>services.</em></h1>
        <?php if (count($errors) > 0): ?><div class="alert alert-danger" role="alert"><strong>Please review the following:</strong><ul class="mb-0 mt-2"><?php foreach ($errors as $error): ?><li><?php echo admin_escape($error); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
        <?php if ($success_message !== ''): ?><div class="alert alert-success" role="status"><?php echo admin_escape($success_message); ?></div><?php endif; ?>
        <section class="admin-form-panel" aria-labelledby="form-title"><h2 id="form-title"><?php echo $editing_service === null ? 'Add a service' : 'Edit service'; ?></h2><form method="post" action="services.php" novalidate><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?php echo admin_escape($editing_service ?? ''); ?>"><div class="row g-3"><div class="col-md-6"><label for="service_name">Service name</label><input class="form-control" type="text" id="service_name" name="service_name" value="<?php echo admin_escape($form_values['service_name']); ?>" required></div><div class="col-md-6"><label for="category">Category</label><input class="form-control" type="text" id="category" name="category" value="<?php echo admin_escape($form_values['category']); ?>" required></div><div class="col-12"><label for="description">Description</label><textarea class="form-control" id="description" name="description" rows="4" required><?php echo admin_escape($form_values['description']); ?></textarea></div><div class="col-md-6"><label for="price">Price</label><input class="form-control" type="number" id="price" name="price" value="<?php echo admin_escape($form_values['price']); ?>" min="0" step="0.01" required></div><div class="col-md-6"><label for="duration">Duration (minutes)</label><input class="form-control" type="number" id="duration" name="duration" value="<?php echo admin_escape($form_values['duration']); ?>" min="1" step="1" required></div></div><div class="admin-form-actions"><button class="btn btn-primary-custom" type="submit"><?php echo $editing_service === null ? 'Add service' : 'Save changes'; ?></button><?php if ($editing_service !== null): ?><a class="text-link" href="services.php">Cancel edit</a><?php endif; ?></div></form></section>
        <section class="admin-list-panel" aria-labelledby="list-title"><div class="admin-list-heading"><h2 id="list-title">All services</h2><span><?php echo count($services); ?> listed</span></div><?php if (count($services) === 0): ?><p class="admin-muted">No services have been added yet.</p><?php else: ?><div class="table-responsive"><table class="table admin-table"><caption class="visually-hidden">All salon services</caption><thead><tr><th scope="col">Service</th><th scope="col">Category</th><th scope="col">Price</th><th scope="col">Duration</th><th scope="col"><span class="visually-hidden">Actions</span></th></tr></thead><tbody><?php foreach ($services as $service): ?><tr><td><strong><?php echo admin_escape($service['service_name']); ?></strong><small><?php echo admin_escape($service['description']); ?></small></td><td><?php echo admin_escape($service['category']); ?></td><td>$<?php echo number_format((float) $service['price'], 2); ?></td><td><?php echo admin_escape($service['duration']); ?> min</td><td class="admin-actions"><a href="services.php?edit=<?php echo (int) $service['id']; ?>">Edit</a><form method="post" action="services.php" onsubmit="return confirm('Delete this service?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?php echo (int) $service['id']; ?>"><button type="submit">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
    </div></main>
    <footer class="site-footer"><div class="container"><p>© <?php echo date('Y'); ?> The Glam Room. Admin services management.</p></div></footer>
</body>
</html>