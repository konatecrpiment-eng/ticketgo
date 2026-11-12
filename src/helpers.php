<?php

declare(strict_types=1);

function load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

function env(string $key, string $default = ''): string
{
    return $_ENV[$key] ?? getenv($key) ?: $default;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $page = 'home', array $params = []): string
{
    $params = array_merge(['page' => $page], $params);
    return 'index.php?' . http_build_query($params);
}

function redirect(string $page, array $params = []): never
{
    header('Location: ' . url($page, $params));
    exit;
}

function money(int $amount): string
{
    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function format_datetime(string $date): string
{
    $ts = strtotime($date);
    $days = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
    return $days[(int) date('w', $ts)] . ' ' . date('d/m/Y \à H\hi', $ts);
}

function format_time(string $date): string
{
    return date('H\hi', strtotime($date));
}

function flash(?string $message = null, string $type = 'info'): ?array
{
    if ($message !== null) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
        return null;
    }
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Session expirée ou requête invalide. Rechargez la page.');
    }
}

function current_user(): ?array
{
    static $user = false;
    if ($user === false) {
        $user = null;
        if (!empty($_SESSION['user_id'])) {
            $stmt = Database::pdo()->prepare('SELECT id, full_name, email, phone FROM users WHERE id = ?');
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch() ?: null;
        }
    }
    return $user;
}

function require_login(): array
{
    $user = current_user();
    if ($user === null) {
        flash('Connectez-vous pour continuer.', 'warning');
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        redirect('login');
    }
    return $user;
}

function render(string $view, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $title = $title ?? env('APP_NAME', 'TicketGo');
    ob_start();
    require ROOT_PATH . '/templates/' . $view . '.php';
    $content = ob_get_clean();
    require ROOT_PATH . '/templates/layout.php';
}

function cities(): array
{
    return ['Abidjan', 'Yamoussoukro', 'Bouaké', 'Korhogo', 'San-Pédro', 'Daloa', 'Man', 'Abengourou'];
}

function payment_methods(): array
{
    return [
        'orange_money' => 'Orange Money',
        'mtn_momo' => 'MTN MoMo',
        'moov_money' => 'Moov Money',
        'wave' => 'Wave',
    ];
}

/** URL échappée, à utiliser dans les attributs HTML (href, action). */
function u(string $page = 'home', array $params = []): string
{
    return e(url($page, $params));
}
