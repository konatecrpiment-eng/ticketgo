<?php

declare(strict_types=1);

if (current_user()) {
    redirect('home');
}

$error = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = (string) ($_POST['email'] ?? '');
    $id = (new UserService(Database::pdo()))->authenticate($email, (string) ($_POST['password'] ?? ''));
    if ($id !== null) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $target = $_SESSION['redirect_after_login'] ?? '';
        unset($_SESSION['redirect_after_login']);
        // Redirection interne uniquement
        if (is_string($target) && preg_match('#^(/[^/]*)?/?index\.php\?page=#', $target)) {
            header('Location: ' . $target);
            exit;
        }
        redirect('home');
    }
    $error = 'E-mail ou mot de passe incorrect.';
}

render('login', ['title' => 'Connexion', 'error' => $error, 'email' => $email]);
