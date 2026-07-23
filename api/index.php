<?php

// Tentukan root aplikasi Laravel
define('LARAVEL_START', microtime(true));

// Arahkan ke index.php milik Laravel di folder public
require __DIR__ . '/../public/index.php';
