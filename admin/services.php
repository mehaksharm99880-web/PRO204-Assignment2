<?php

require_once '../config/auth.php';
require_once '../config/database.php';
require_once '../config/ServiceManager.php';

require_admin();

$service_manager = new ServiceManager($conn);

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

/**
 * Clean submitted form values.
 */
function clean_value($value): string
{
    return trim((string) $value);
}

/**
 * Validate a positive integer ID.
 */
function valid_service_id($value): bool
{
    return filter_var(
        $value,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    ) !== false;
}

/**
 * Validate service form values.
 */
function validate_service_form(array $values): array
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

    if (
        $values['price'] === '' ||
        !is_numeric($values['price']) ||
        (float) $values['price'] < 0
    ) {
        $validation_errors[] =
            'Price must be a number that is zero or greater.';
    }

    if (
        $values['duration'] === '' ||
        !valid_service_id($values['duration'])
    ) {
        $validation_errors[] =
            'Duration must be a positive whole number of minutes.';
    }

    return $validation_errors;
}

/**
 * Escape output safely for HTML.
 */
function admin_escape($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

/*
|--------------------------------------------------------------------------
| POST actions
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
     * Verify CSRF token for every POST request.
     */
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {

        $action = $_POST['action'] ?? '';

        /*
         * Logout
         */
        if ($action === 'logout') {

            logout_admin();

            header('Location: login.php');
            exit;
        }

        /*
         * Delete service
         */
        elseif ($action === 'delete') {

            $service_id = $_POST['id'] ?? '';

            if (!valid_service_id($service_id)) {

                $errors[] =
                    'A valid service ID is required to delete a service.';

            } else {

                $service_id = (int) $service_id;

                $existing_service =
                    $service_manager->getById($service_id);

                if ($existing_service === null) {

                    $errors[] =
                        'The requested service could not be found.';

                } elseif ($service_manager->delete($service_id)) {

                    $success_message =
                        'Service deleted successfully.';

                } else {

                    $errors[] =
                        'The service could not be deleted. Please try again.';
                }
            }
        }

        /*
         * Add or update service
         */
        elseif ($action === 'save') {

            $service_id = $_POST['id'] ?? '';

            $form_values = [
                'service_name' =>
                    clean_value($_POST['service_name'] ?? ''),

                'category' =>
                    clean_value($_POST['category'] ?? ''),

                'description' =>
                    clean_value($_POST['description'] ?? ''),

                'price' =>
                    clean_value($_POST['price'] ?? ''),

                'duration' =>
                    clean_value($_POST['duration'] ?? ''),
            ];

            $errors = validate_service_form($form_values);

            /*
             * Determine whether this is an update.
             */
            if (valid_service_id($service_id)) {

                $editing_service = (int) $service_id;

            } elseif ($service_id !== '') {

                $errors[] = 'The service ID is invalid.';
            }

            /*
             * Only perform database operation when validation passes.
             */
            if (count($errors) === 0) {

                $service_name = $form_values['service_name'];
                $category = $form_values['category'];
                $description = $form_values['description'];
                $price = (float) $form_values['price'];
                $duration = (int) $form_values['duration'];

                /*
                 * Update existing service.
                 */
                if ($editing_service !== null) {

                    $existing_service =
                        $service_manager->getById($editing_service);

                    if ($existing_service === null) {

                        $errors[] =
                            'The requested service could not be found.';

                    } elseif (
                        $service_manager->update(
                            $editing_service,
                            $service_name,
                            $category,
                            $description,
                            $price,
                            $duration
                        )
                    ) {

                        $success_message =
                            'Service updated successfully.';

                    } else {

                        $errors[] =
                            'The service could not be updated. Please try again.';
                    }

                /*
                 * Create new service.
                 */
                } else {

                    if (
                        $service_manager->create(
                            $service_name,
                            $category,
                            $description,
                            $price,
                            $duration
                        )
                    ) {

                        $success_message =
                            'Service added successfully.';

                    } else {

                        $errors[] =
                            'The service could not be saved. Please check the database and try again.';
                    }
                }

                /*
                 * Clear form after successful operation.
                 */
                if (count($errors) === 0) {

                    $form_values = [
                        'service_name' => '',
                        'category' => '',
                        'description' => '',
                        'price' => '',
                        'duration' => '',
                    ];

                    $editing_service = null;
                }
            }
        }
    }
}

/*
|--------------------------------------------------------------------------
| Load service for editing
|--------------------------------------------------------------------------
*/

if (isset($_GET['edit'])) {

    if (!valid_service_id($_GET['edit'])) {

        $errors[] =
            'The requested service ID is invalid.';

    } else {

        $edit_id = (int) $_GET['edit'];

        $service = $service_manager->getById($edit_id);

        if ($service !== null) {

            $editing_service = $edit_id;

            $form_values = [
                'service_name' =>
                    $service['service_name'],

                'category' =>
                    $service['category'],

                'description' =>
                    $service['description'],

                'price' =>
                    $service['price'],

                'duration' =>
                    $service['duration'],
            ];

        } else {

            $errors[] =
                'The requested service could not be found.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| Load all services
|--------------------------------------------------------------------------
*/

$services = $service_manager->getAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Services | The Glam Room</title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="../assets/css/services.css"
    >

</head>

<body class="admin-page">

<header class="services-header">

    <nav class="navbar">

        <div class="container">

            <a
                class="navbar-brand"
                href="../index.php"
            >

                <span class="brand-mark">G</span>

                <span>
                    The Glam <em>Room</em>
                </span>

            </a>

            <div class="d-flex align-items-center gap-3">

                <a
                    class="nav-link"
                    href="../services.php"
                >
                    View public services ↗
                </a>

                <form
                    method="post"
                    action="services.php"
                    class="m-0"
                >

                    <input
                        type="hidden"
                        name="action"
                        value="logout"
                    >

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?php echo admin_escape(csrf_token()); ?>"
                    >

                    <button
                        type="submit"
                        class="btn btn-link nav-link p-0"
                    >
                        Logout
                    </button>

                </form>

            </div>

        </div>

    </nav>

</header>

<main class="admin-main">

    <div class="container">

        <p class="eyebrow">
            Management area
        </p>

        <h1>
            Manage <em>services.</em>
        </h1>

        <?php if (count($errors) > 0): ?>

            <div
                class="alert alert-danger"
                role="alert"
            >

                <strong>
                    Please review the following:
                </strong>

                <ul class="mb-0 mt-2">

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?php echo admin_escape($error); ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <?php if ($success_message !== ''): ?>

            <div
                class="alert alert-success"
                role="status"
            >
                <?php echo admin_escape($success_message); ?>
            </div>

        <?php endif; ?>


        <section
            class="admin-form-panel"
            aria-labelledby="form-title"
        >

            <h2 id="form-title">

                <?php
                echo $editing_service === null
                    ? 'Add a service'
                    : 'Edit service';
                ?>

            </h2>

            <form
                method="post"
                action="services.php"
                novalidate
            >

                <input
                    type="hidden"
                    name="action"
                    value="save"
                >

                <input
                    type="hidden"
                    name="id"
                    value="<?php echo admin_escape($editing_service ?? ''); ?>"
                >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?php echo admin_escape(csrf_token()); ?>"
                >

                <div class="row g-3">

                    <div class="col-md-6">

                        <label for="service_name">
                            Service name
                        </label>

                        <input
                            class="form-control"
                            type="text"
                            id="service_name"
                            name="service_name"
                            value="<?php echo admin_escape($form_values['service_name']); ?>"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label for="category">
                            Category
                        </label>

                        <input
                            class="form-control"
                            type="text"
                            id="category"
                            name="category"
                            value="<?php echo admin_escape($form_values['category']); ?>"
                            required
                        >

                    </div>


                    <div class="col-12">

                        <label for="description">
                            Description
                        </label>

                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="4"
                            required
                        ><?php echo admin_escape($form_values['description']); ?></textarea>

                    </div>


                    <div class="col-md-6">

                        <label for="price">
                            Price
                        </label>

                        <input
                            class="form-control"
                            type="number"
                            id="price"
                            name="price"
                            value="<?php echo admin_escape($form_values['price']); ?>"
                            min="0"
                            step="0.01"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label for="duration">
                            Duration (minutes)
                        </label>

                        <input
                            class="form-control"
                            type="number"
                            id="duration"
                            name="duration"
                            value="<?php echo admin_escape($form_values['duration']); ?>"
                            min="1"
                            step="1"
                            required
                        >

                    </div>

                </div>


                <div class="admin-form-actions">

                    <button
                        class="btn btn-primary-custom"
                        type="submit"
                    >

                        <?php
                        echo $editing_service === null
                            ? 'Add service'
                            : 'Save changes';
                        ?>

                    </button>


                    <?php if ($editing_service !== null): ?>

                        <a
                            class="text-link"
                            href="services.php"
                        >
                            Cancel edit
                        </a>

                    <?php endif; ?>

                </div>

            </form>

        </section>


        <section
            class="admin-list-panel"
            aria-labelledby="list-title"
        >

            <div class="admin-list-heading">

                <h2 id="list-title">
                    All services
                </h2>

                <span>
                    <?php echo count($services); ?> listed
                </span>

            </div>


            <?php if (count($services) === 0): ?>

                <p class="admin-muted">
                    No services have been added yet.
                </p>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table admin-table">

                        <caption class="visually-hidden">
                            All salon services
                        </caption>

                        <thead>

                            <tr>

                                <th scope="col">
                                    Service
                                </th>

                                <th scope="col">
                                    Category
                                </th>

                                <th scope="col">
                                    Price
                                </th>

                                <th scope="col">
                                    Duration
                                </th>

                                <th scope="col">
                                    <span class="visually-hidden">
                                        Actions
                                    </span>
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($services as $service): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?php
                                            echo admin_escape(
                                                $service['service_name']
                                            );
                                            ?>
                                        </strong>

                                        <small>
                                            <?php
                                            echo admin_escape(
                                                $service['description']
                                            );
                                            ?>
                                        </small>

                                    </td>


                                    <td>
                                        <?php
                                        echo admin_escape(
                                            $service['category']
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        $<?php
                                        echo number_format(
                                            (float) $service['price'],
                                            2
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        <?php
                                        echo admin_escape(
                                            $service['duration']
                                        );
                                        ?>
                                        min
                                    </td>


                                    <td class="admin-actions">

                                        <a
                                            href="services.php?edit=<?php echo (int) $service['id']; ?>"
                                        >
                                            Edit
                                        </a>


                                        <form
                                            method="post"
                                            action="services.php"
                                            onsubmit="return confirm('Delete this service?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="delete"
                                            >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?php echo (int) $service['id']; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?php echo admin_escape(csrf_token()); ?>"
                                            >

                                            <button type="submit">
                                                Delete
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </section>

    </div>

</main>


<footer class="site-footer">

    <div class="container">

        <p>
            © <?php echo date('Y'); ?>
            The Glam Room. Admin services management.
        </p>

    </div>

</footer>

</body>
</html>