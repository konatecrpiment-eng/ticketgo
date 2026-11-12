<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $_SESSION = [];
    session_destroy();
    session_start();
    flash('Vous êtes déconnecté.', 'info');
}
redirect('home');
