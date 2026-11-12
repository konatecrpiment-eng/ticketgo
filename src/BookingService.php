<?php

declare(strict_types=1);

final class BookingException extends RuntimeException
{
}

final class BookingService
{
    private const SEATS_LEFT_SQL = "(t.seats_total - COALESCE((SELECT SUM(b.passengers_count)
        FROM bookings b WHERE b.trip_id = t.id AND b.status = 'paid'), 0))";

    public function __construct(private PDO $db)
    {
    }

    public function searchTrips(string $from, string $to, string $date): array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, ' . self::SEATS_LEFT_SQL . ' AS seats_left
             FROM trips t
             WHERE t.origin = :from AND t.destination = :to
               AND date(t.departure_at) = :date AND t.departure_at > :now
             ORDER BY t.departure_at'
        );
        $stmt->execute([':from' => $from, ':to' => $to, ':date' => $date, ':now' => date('Y-m-d H:i:s')]);
        return $stmt->fetchAll();
    }

    public function getTrip(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, ' . self::SEATS_LEFT_SQL . ' AS seats_left FROM trips t WHERE t.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Crée une réservation payée (paiement Mobile Money simulé).
     *
     * @param string[] $passengerNames
     * @return string Référence du billet
     */
    public function createBooking(
        int $userId,
        int $tripId,
        array $passengerNames,
        string $method,
        string $phone
    ): string {
        $names = array_values(array_filter(array_map('trim', $passengerNames), fn ($n) => $n !== ''));
        $count = count($names);
        if ($count < 1 || $count > 5) {
            throw new BookingException('Vous pouvez réserver de 1 à 5 passagers.');
        }
        foreach ($names as $name) {
            if (mb_strlen($name) < 2 || mb_strlen($name) > 80) {
                throw new BookingException('Chaque nom de passager doit contenir entre 2 et 80 caractères.');
            }
        }
        if (!array_key_exists($method, payment_methods())) {
            throw new BookingException('Moyen de paiement inconnu.');
        }
        $phone = preg_replace('/\s+/', '', $phone);
        if (!preg_match('/^\+?\d{8,14}$/', $phone)) {
            throw new BookingException('Numéro de paiement invalide.');
        }

        $this->db->exec('BEGIN IMMEDIATE');
        try {
            $trip = $this->getTrip($tripId);
            if ($trip === null || $trip['departure_at'] <= date('Y-m-d H:i:s')) {
                throw new BookingException('Ce trajet n\'est plus disponible.');
            }
            if ((int) $trip['seats_left'] < $count) {
                throw new BookingException('Il ne reste pas assez de places sur ce trajet.');
            }

            $reference = 'TK-' . strtoupper(bin2hex(random_bytes(4)));
            $total = (int) $trip['price'] * $count;
            $this->db->prepare(
                'INSERT INTO bookings (reference, user_id, trip_id, passengers_count, total, payment_method, status)
                 VALUES (?, ?, ?, ?, ?, ?, \'paid\')'
            )->execute([$reference, $userId, $tripId, $count, $total, $method]);
            $bookingId = (int) $this->db->lastInsertId();

            $free = $this->freeSeats($tripId, (int) $trip['seats_total']);
            $insert = $this->db->prepare(
                'INSERT INTO passengers (booking_id, full_name, seat_number) VALUES (?, ?, ?)'
            );
            foreach ($names as $i => $name) {
                $insert->execute([$bookingId, $name, $free[$i]]);
            }
            $this->db->exec('COMMIT');
            return $reference;
        } catch (Throwable $e) {
            $this->db->exec('ROLLBACK');
            throw $e;
        }
    }

    /** @return int[] Numéros de sièges libres, ordre croissant */
    private function freeSeats(int $tripId, int $total): array
    {
        $stmt = $this->db->prepare(
            "SELECT p.seat_number FROM passengers p
             JOIN bookings b ON b.id = p.booking_id
             WHERE b.trip_id = ? AND b.status = 'paid'"
        );
        $stmt->execute([$tripId]);
        $taken = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
        return array_values(array_diff(range(1, $total), $taken));
    }

    public function userBookings(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, t.origin, t.destination, t.departure_at, t.company
             FROM bookings b JOIN trips t ON t.id = b.trip_id
             WHERE b.user_id = ? ORDER BY t.departure_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findBooking(string $reference, int $userId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT b.*, t.origin, t.destination, t.departure_at, t.arrival_at, t.company, t.bus_type
             FROM bookings b JOIN trips t ON t.id = b.trip_id
             WHERE b.reference = ? AND b.user_id = ?'
        );
        $stmt->execute([$reference, $userId]);
        $booking = $stmt->fetch();
        if (!$booking) {
            return null;
        }
        $p = $this->db->prepare('SELECT full_name, seat_number FROM passengers WHERE booking_id = ? ORDER BY seat_number');
        $p->execute([$booking['id']]);
        $booking['passengers'] = $p->fetchAll();
        return $booking;
    }

    public function canCancel(array $booking, int $limitHours): bool
    {
        return $booking['status'] === 'paid'
            && strtotime($booking['departure_at']) > time() + $limitHours * 3600;
    }

    public function cancelBooking(string $reference, int $userId, int $limitHours): void
    {
        $booking = $this->findBooking($reference, $userId);
        if ($booking === null) {
            throw new BookingException('Billet introuvable.');
        }
        if (!$this->canCancel($booking, $limitHours)) {
            throw new BookingException(
                "Ce billet ne peut plus être annulé (délai limite : {$limitHours} h avant le départ)."
            );
        }
        $this->db->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ?")->execute([$booking['id']]);
    }
}
