<?php

require_once 'config/auth.php';
require_once 'config/database.php';

$form_values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'service_id' => '',
    'message' => '',
];

$services = [];
$errors = [];
$success_message = '';

function contact_escape($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function contact_valid_id($value): bool
{
    return filter_var(
        $value,
        FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1]]
    ) !== false;
}

function contact_load_services(mysqli $conn): ?array
{
    $loaded_services = [];

    $statement = $conn->prepare(
        'SELECT id, service_name, category
         FROM services
         ORDER BY category, service_name'
    );

    if ($statement === false || !$statement->execute()) {

        if ($statement !== false) {
            $statement->close();
        }

        return null;
    }

    $statement->bind_result(
        $id,
        $service_name,
        $category
    );

    while ($statement->fetch()) {

        $loaded_services[] = [
            'id' => $id,
            'service_name' => $service_name,
            'category' => $category,
        ];
    }

    $statement->close();

    return $loaded_services;
}

function contact_service_exists(
    mysqli $conn,
    int $service_id
): bool {
    $statement = $conn->prepare(
        'SELECT id
         FROM services
         WHERE id = ?'
    );

    if ($statement === false) {
        return false;
    }

    $statement->bind_param(
        'i',
        $service_id
    );

    if (!$statement->execute()) {

        $statement->close();

        return false;
    }

    $statement->store_result();

    $exists = $statement->num_rows === 1;

    $statement->close();

    return $exists;
}

$services = contact_load_services($conn);

if ($services === null) {

    $services = [];

    $errors[] =
        'Our service list is temporarily unavailable. Please try again later.';
}

/*
|--------------------------------------------------------------------------
| Process enquiry form
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && count($errors) === 0
) {

    /*
     * Verify CSRF token before processing form data.
     */
    if (
        !verify_csrf_token(
            $_POST['csrf_token'] ?? null
        )
    ) {

        $errors[] =
            'Invalid security token. Please refresh the page and try again.';

    } else {

        $form_values = [
            'name' =>
                trim((string) ($_POST['name'] ?? '')),

            'email' =>
                trim((string) ($_POST['email'] ?? '')),

            'phone' =>
                trim((string) ($_POST['phone'] ?? '')),

            'service_id' =>
                trim((string) ($_POST['service_id'] ?? '')),

            'message' =>
                trim((string) ($_POST['message'] ?? '')),
        ];

        /*
         * Validate name.
         */
        if ($form_values['name'] === '') {

            $errors[] =
                'Please enter your name.';

        } elseif (mb_strlen($form_values['name']) > 100) {

            $errors[] =
                'Name must be 100 characters or fewer.';
        }

        /*
         * Validate email.
         */
        if ($form_values['email'] === '') {

            $errors[] =
                'Please enter your email address.';

        } elseif (
            !filter_var(
                $form_values['email'],
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $errors[] =
                'Please enter a valid email address.';

        } elseif (mb_strlen($form_values['email']) > 150) {

            $errors[] =
                'Email address must be 150 characters or fewer.';
        }

        /*
         * Validate optional phone number.
         */
        if (
            $form_values['phone'] !== ''
            && mb_strlen($form_values['phone']) > 30
        ) {

            $errors[] =
                'Phone number must be 30 characters or fewer.';
        }

        /*
         * Validate service selection.
         */
        if (
            $form_values['service_id'] === ''
            || !contact_valid_id(
                $form_values['service_id']
            )
        ) {

            $errors[] =
                'Please choose a service.';

        } elseif (
            !contact_service_exists(
                $conn,
                (int) $form_values['service_id']
            )
        ) {

            $errors[] =
                'Please choose a valid service from the list.';
        }

        /*
         * Validate message.
         */
        if ($form_values['message'] === '') {

            $errors[] =
                'Please enter a message.';

        } elseif (mb_strlen($form_values['message']) > 5000) {

            $errors[] =
                'Message must be 5000 characters or fewer.';
        }

        /*
         * Save enquiry if validation succeeds.
         */
        if (count($errors) === 0) {

            $service_id =
                (int) $form_values['service_id'];

            $statement = $conn->prepare(
                'INSERT INTO enquiries
                (name, email, phone, service_id, message)
                VALUES (?, ?, ?, ?, ?)'
            );

            if ($statement === false) {

                $errors[] =
                    'Your enquiry could not be sent right now. Please try again later.';

            } else {

                $statement->bind_param(
                    'sssis',
                    $form_values['name'],
                    $form_values['email'],
                    $form_values['phone'],
                    $service_id,
                    $form_values['message']
                );

                if (!$statement->execute()) {

                    $errors[] =
                        'Your enquiry could not be sent right now. Please try again later.';

                } else {

                    $success_message =
                        'Thank you. Your enquiry has been sent, and our team will be in touch soon.';

                    $form_values = [
                        'name' => '',
                        'email' => '',
                        'phone' => '',
                        'service_id' => '',
                        'message' => '',
                    ];

                    /*
                     * Generate a fresh CSRF token for the next form submission.
                     */
                    $_SESSION['csrf_token'] =
                        bin2hex(random_bytes(32));
                }

                $statement->close();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Contact The Glam Room to make a beauty appointment enquiry."
    >

    <title>Contact | The Glam Room</title>

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
        href="assets/css/style.css"
    >

    <link
        rel="stylesheet"
        href="assets/css/contact.css"
    >

</head>

<body>

    <a
        class="skip-link"
        href="#main-content"
    >
        Skip to main content
    </a>

    <header
        class="site-header contact-header"
        id="home"
    >

        <nav
            class="navbar navbar-expand-lg"
            aria-label="Primary navigation"
        >

            <div class="container">

                <a
                    class="navbar-brand"
                    href="index.php"
                    aria-label="The Glam Room home"
                >

                    <span class="brand-mark">G</span>

                    <span>
                        The Glam <em>Room</em>
                    </span>

                </a>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#main-navigation"
                    aria-controls="main-navigation"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div
                    class="collapse navbar-collapse"
                    id="main-navigation"
                >

                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="index.php"
                            >
                                Home
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="index.php#about"
                            >
                                About
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                href="services.php"
                            >
                                Services
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link nav-link-cta active"
                                href="contact.php"
                                aria-current="page"
                            >
                                Contact
                                <span aria-hidden="true">↗</span>
                            </a>
                        </li>

                    </ul>

                </div>

            </div>

        </nav>

    </header>


    <main id="main-content">

        <section
            class="contact-intro section-spacing"
            aria-labelledby="contact-title"
        >

            <div class="container">

                <p class="eyebrow">
                    Come say hello
                </p>

                <h1 id="contact-title">
                    Let’s make time<br>
                    <em>for you.</em>
                </h1>

                <p class="section-lead">
                    Tell us what you have in mind and we’ll get back to you soon.
                </p>

            </div>

        </section>


        <section
            class="contact-form-section"
            aria-labelledby="enquiry-title"
        >

            <div class="container">

                <div class="contact-form-layout">

                    <div class="contact-form-aside">

                        <p class="eyebrow">
                            Your enquiry
                        </p>

                        <h2 id="enquiry-title">
                            A little note<br>
                            goes a long way.
                        </h2>

                        <p>
                            We’re here to help you find the right treatment and time for your visit.
                        </p>

                        <address>
                            12 Willow Lane<br>
                            Melbourne, VIC 3000
                        </address>

                        <p>
                            <a href="tel:+61390001234">
                                (03) 9000 1234
                            </a>
                            <br>

                            <a href="mailto:hello@theglamroom.com.au">
                                hello@theglamroom.com.au
                            </a>
                        </p>

                    </div>


                    <div class="contact-form-wrap">

                        <?php if (count($errors) > 0): ?>

                            <div
                                class="alert alert-danger contact-alert"
                                role="alert"
                                aria-live="assertive"
                            >

                                <strong>
                                    Please review your enquiry:
                                </strong>

                                <ul>

                                    <?php foreach ($errors as $error): ?>

                                        <li>
                                            <?php
                                            echo contact_escape($error);
                                            ?>
                                        </li>

                                    <?php endforeach; ?>

                                </ul>

                            </div>

                        <?php endif; ?>


                        <?php if ($success_message !== ''): ?>

                            <div
                                class="alert alert-success contact-alert"
                                role="status"
                                aria-live="polite"
                            >

                                <?php
                                echo contact_escape(
                                    $success_message
                                );
                                ?>

                            </div>

                        <?php endif; ?>


                        <form
                            method="post"
                            action="contact.php"
                            novalidate
                        >

                            <!-- CSRF protection -->
                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?php echo contact_escape(csrf_token()); ?>"
                            >

                            <div class="row g-4">

                                <div class="col-md-6">

                                    <label for="name">
                                        Name
                                        <span aria-hidden="true">*</span>
                                    </label>

                                    <input
                                        class="form-control"
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="<?php echo contact_escape($form_values['name']); ?>"
                                        maxlength="100"
                                        autocomplete="name"
                                        required
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="email">
                                        Email
                                        <span aria-hidden="true">*</span>
                                    </label>

                                    <input
                                        class="form-control"
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="<?php echo contact_escape($form_values['email']); ?>"
                                        maxlength="150"
                                        autocomplete="email"
                                        required
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="phone">
                                        Phone
                                    </label>

                                    <input
                                        class="form-control"
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="<?php echo contact_escape($form_values['phone']); ?>"
                                        maxlength="30"
                                        autocomplete="tel"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label for="service_id">
                                        Service
                                        <span aria-hidden="true">*</span>
                                    </label>

                                    <select
                                        class="form-select"
                                        id="service_id"
                                        name="service_id"
                                        required
                                    >

                                        <option value="">
                                            Choose a service
                                        </option>

                                        <?php foreach ($services as $service): ?>

                                            <option
                                                value="<?php echo (int) $service['id']; ?>"
                                                <?php
                                                echo
                                                    (string) $form_values['service_id']
                                                    ===
                                                    (string) $service['id']
                                                    ? 'selected'
                                                    : '';
                                                ?>
                                            >

                                                <?php
                                                echo contact_escape(
                                                    $service['service_name']
                                                    . ' · '
                                                    . $service['category']
                                                );
                                                ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <div class="col-12">

                                    <label for="message">
                                        Message
                                        <span aria-hidden="true">*</span>
                                    </label>

                                    <textarea
                                        class="form-control"
                                        id="message"
                                        name="message"
                                        rows="6"
                                        maxlength="5000"
                                        required
                                    ><?php echo contact_escape($form_values['message']); ?></textarea>

                                </div>

                            </div>


                            <p class="required-note">
                                <span aria-hidden="true">*</span>
                                Required fields
                            </p>


                            <button
                                class="btn btn-primary-custom"
                                type="submit"
                            >
                                Send enquiry
                                <span aria-hidden="true">↗</span>
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </section>

    </main>


    <footer class="site-footer">

        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">

            <a
                class="footer-brand"
                href="index.php"
            >
                The Glam <em>Room</em>
            </a>

            <p>
                © <?php echo date('Y'); ?>
                The Glam Room. Beauty, thoughtfully done.
            </p>

            <a
                href="#main-content"
                class="back-to-top"
            >
                Back to top ↑
            </a>

        </div>

    </footer>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"
    ></script>

    <script src="assets/js/main.js"></script>

</body>

</html>