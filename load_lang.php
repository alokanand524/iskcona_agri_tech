<?php
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

$lang = $_SESSION['lang'] ?? 'en';
$lang_file = __DIR__ . "/lang/$lang.php";

if (file_exists($lang_file)) {
    include $lang_file;
} else {
    include __DIR__ . "/lang/en.php";
}

if (!function_exists('__')) {
    function __($key) {
        global $translations;
        return $translations[$key] ?? $key;
    }
}
