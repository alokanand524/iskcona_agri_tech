<?php
    session_start();

    // to sure only logged-in users can log out
    if (!isset($_SESSION['admin_username'])) {
        header("Location: ../admin/login.php");
        exit;
    }

    // Unset all session variables
    $_SESSION = [];

    // Destroy the session
    session_destroy();

    // Prevent caching
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

    // Redirect to login
    header("Location: ../admin/login.php");
exit;
