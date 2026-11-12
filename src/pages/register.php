<?php

declare(strict_types=1);

if (current_user()) {
    redirect('home');
}

$errors = [];
$old = ['name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $old = [
        'name' => (string) ($_POST['name'] ?? ''),
        'email' => (string) ($_POST['email'] ?? ''),
        'phone' => (string) ($_POST['phone'] ?? ''),
    ];
    $result = (new UserService(Database::pdo()))->register(
        $old['name'],
        $old['email'],
        $old['phone'],
        (string) ($_POST['password'] ?? '')
    );
    if (!$result['errors']) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $result['id'];
        flash('Bienvenue sur TicketGo ! Cherchez votre premier trajet.', 'success');
        redirect('home');
    }
    $errors = $result['errors'];
}

render('register', ['title' => 'Créer un compte', 'errors' => $errors, 'old' => $old]);
