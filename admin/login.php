<?php

require_once '../config/auth.php';
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid security token. Please try again.';
    } else {

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = 'Please enter both username and password.';
        } else {

            $stmt = $conn->prepare(
                "SELECT id, username, password_hash
                 FROM admin_users
                 WHERE username = ?
                 LIMIT 1"
            );

            if ($stmt) {

                $stmt->bind_param('s', $username);
                $stmt->execute();

                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();

                if ($admin && password_verify($password, $admin['password_hash'])) {

                    // Prevent session fixation
                    session_regenerate_id(true);

                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];

                    header('Location: services.php');
                    exit;

                } else {
                    $error = 'Invalid username or password.';
                }

                $stmt->close();

            } else {
                $error = 'Unable to process login. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login - The Glam Room</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #111111, #2b1a24);
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        }

        .brand {
            text-align: center;
            margin-bottom: 25px;
        }

        .brand h1 {
            font-weight: 700;
            color: #111111;
        }

        .brand p {
            color: #777777;
            margin-bottom: 0;
        }

        .btn-login {
            background: #111111;
            color: #ffffff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #d4af37;
            color: #111111;
        }

        .form-control {
            border-radius: 10px;
            padding: 11px;
        }
    </style>

</head>

<body>

<div class="login-card">

    <div class="brand">
        <h1>The Glam Room</h1>
        <p>Admin Login</p>
    </div>

    <?php if ($error !== ''): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>

    <?php endif; ?>

    <form method="POST" action="">

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>"
        >

        <div class="mb-3">

            <label for="username" class="form-label">
                Username
            </label>

            <input
                type="text"
                class="form-control"
                id="username"
                name="username"
                required
                autocomplete="username"
            >

        </div>

        <div class="mb-4">

            <label for="password" class="form-label">
                Password
            </label>

            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                required
                autocomplete="current-password"
            >

        </div>

        <button type="submit" class="btn-login">
            Login
        </button>

    </form>

</div>

</body>
</html>