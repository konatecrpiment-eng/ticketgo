<?php

declare(strict_types=1);

require __DIR__ . '/../src/bootstrap.php';

$routes = ['home', 'search', 'book', 'tickets', 'ticket', 'cancel', 'login', 'register', 'logout'];
$page = $_GET['page'] ?? 'home';

if (!is_string($page) || !in_array($page, $routes, true)) {
    http_response_code(404);
    render('404', ['title' => 'Page introuvable']);
    exit;
}

try {
    require ROOT_PATH . '/src/pages/' . $page . '.php';
} catch (PDOException $e) {
    http_response_code(500);
    $hint = env('APP_ENV') === 'development' ? $e->getMessage() : 'Erreur technique, réessayez plus tard.';
    render('error', ['title' => 'Erreur', 'message' => $hint . ' (avez-vous lancé « php database/init.php » ?)']);
}
