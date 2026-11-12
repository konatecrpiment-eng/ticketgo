<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

date_default_timezone_set('Africa/Abidjan');

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/BookingService.php';
require_once __DIR__ . '/UserService.php';

load_env(ROOT_PATH . '/.env');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}
