<?php

declare(strict_types=1);

$user = require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('tickets');
}
csrf_check();

$reference = (string) ($_POST['ref'] ?? '');
try {
    (new BookingService(Database::pdo()))->cancelBooking($reference, $user['id'], (int) env('CANCEL_LIMIT_HOURS', '2'));
    flash('Billet annulé. Le remboursement sera effectué sur votre compte Mobile Money.', 'success');
} catch (BookingException $e) {
    flash($e->getMessage(), 'error');
}
redirect('ticket', ['ref' => $reference]);
