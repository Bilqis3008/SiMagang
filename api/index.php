<?php

// Tentukan root aplikasi Laravel
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Arahkan ke index.php milik Laravel di folder public
require __DIR__ . '/../public/index.php';
