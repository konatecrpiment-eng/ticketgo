<?php

declare(strict_types=1);

$popular = Database::pdo()->query(
    "SELECT origin, destination, MIN(price) AS price FROM trips
     WHERE departure_at > datetime('now') AND origin = 'Abidjan'
     GROUP BY origin, destination ORDER BY price LIMIT 6"
)->fetchAll();

render('home', ['title' => 'TicketGo — Votre billet de bus sans passer par la gare', 'popular' => $popular]);
