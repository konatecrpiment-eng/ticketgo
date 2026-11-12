<?php

declare(strict_types=1);

/**
 * Crée la base de données, applique le schéma et insère des données de démonstration.
 * Usage : php database/init.php [--fresh]
 */

require __DIR__ . '/../src/bootstrap.php';

$dsn = env('DB_DSN', 'sqlite:storage/ticketgo.sqlite');
if (in_array('--fresh', $argv ?? [], true) && str_starts_with($dsn, 'sqlite:')) {
    $file = ROOT_PATH . '/' . substr($dsn, 7);
    if (is_file($file)) {
        unlink($file);
    }
}

$pdo = Database::pdo();
$pdo->exec((string) file_get_contents(__DIR__ . '/schema.sql'));

if ((int) $pdo->query('SELECT COUNT(*) FROM trips')->fetchColumn() === 0) {
    seed_trips($pdo);
    $pdo->prepare('INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)')
        ->execute(['Utilisateur Démo', 'demo@ticketgo.test', '0700000000', password_hash('demo1234', PASSWORD_DEFAULT)]);
    echo "Données de démonstration insérées (compte : demo@ticketgo.test / demo1234).\n";
}
echo "Base de données prête.\n";

function seed_trips(PDO $pdo): void
{
    // origine, destination, durée (min), prix de base (FCFA)
    $routes = [
        ['Abidjan', 'Yamoussoukro', 270, 5000],
        ['Abidjan', 'Bouaké', 360, 7500],
        ['Abidjan', 'Korhogo', 720, 12000],
        ['Abidjan', 'San-Pédro', 420, 8000],
        ['Abidjan', 'Daloa', 420, 7000],
        ['Abidjan', 'Man', 600, 10000],
        ['Abidjan', 'Abengourou', 300, 5500],
        ['Yamoussoukro', 'Abidjan', 270, 5000],
        ['Bouaké', 'Abidjan', 360, 7500],
        ['Korhogo', 'Abidjan', 720, 12000],
        ['San-Pédro', 'Abidjan', 420, 8000],
        ['Daloa', 'Abidjan', 420, 7000],
        ['Man', 'Abidjan', 600, 10000],
        ['Abengourou', 'Abidjan', 300, 5500],
        ['Bouaké', 'Korhogo', 300, 5000],
        ['Korhogo', 'Bouaké', 300, 5000],
    ];
    $companies = ['Savane Express', 'Lagune Transport', 'Ivoire Bus'];
    $slots = [['06:00', 'Standard', 1.0], ['10:30', 'VIP', 1.3], ['15:00', 'Standard', 1.0], ['21:00', 'VIP', 1.3]];

    $stmt = $pdo->prepare(
        'INSERT INTO trips (company, origin, destination, departure_at, arrival_at, price, seats_total, bus_type)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $pdo->beginTransaction();
    for ($day = 0; $day < 21; $day++) {
        $date = date('Y-m-d', strtotime("+{$day} days"));
        foreach ($routes as $r => [$from, $to, $minutes, $price]) {
            foreach ($slots as $s => [$time, $type, $factor]) {
                $dep = strtotime("{$date} {$time}");
                $stmt->execute([
                    $companies[($r + $s) % count($companies)],
                    $from,
                    $to,
                    date('Y-m-d H:i:s', $dep),
                    date('Y-m-d H:i:s', $dep + $minutes * 60),
                    (int) (round($price * $factor / 500) * 500),
                    $type === 'VIP' ? 30 : 50,
                    $type,
                ]);
            }
        }
    }
    $pdo->commit();
}
