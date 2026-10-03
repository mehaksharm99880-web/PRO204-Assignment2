<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Generate and return a CSRF token.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Validate the submitted CSRF token.
 */
function verify_csrf_token(?string $token): bool
{
    if (
        empty($token) ||
        empty($_SESSION['csrf_token'])
    ) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Require the user to be logged in as an admin.
 */
function require_admin(): void
{
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Log out the current admin.
 */
function logout_admin(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}