<?php

declare(strict_types=1);

$from = (string) ($_GET['from'] ?? '');
$to = (string) ($_GET['to'] ?? '');
$date = (string) ($_GET['date'] ?? date('Y-m-d'));
$errors = [];

if (!in_array($from, cities(), true) || !in_array($to, cities(), true)) {
    $errors[] = 'Choisissez une ville de départ et une ville d\'arrivée.';
} elseif ($from === $to) {
    $errors[] = 'Le départ et l\'arrivée doivent être différents.';
}
$d = DateTime::createFromFormat('Y-m-d', $date);
if (!$d || $d->format('Y-m-d') !== $date) {
    $errors[] = 'Date invalide.';
} elseif ($date < date('Y-m-d')) {
    $errors[] = 'La date ne peut pas être dans le passé.';
}

$trips = $errors ? [] : (new BookingService(Database::pdo()))->searchTrips($from, $to, $date);

render('search', [
    'title' => "Trajets {$from} → {$to}",
    'from' => $from,
    'to' => $to,
    'date' => $date,
    'errors' => $errors,
    'trips' => $trips,
]);
