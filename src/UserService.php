<?php

declare(strict_types=1);

final class UserService
{
    public function __construct(private PDO $db)
    {
    }

    /** @return array{errors: string[], id: ?int} */
    public function register(string $name, string $email, string $phone, string $password): array
    {
        $errors = [];
        $name = trim($name);
        $email = strtolower(trim($email));
        $phone = preg_replace('/\s+/', '', $phone);

        if (mb_strlen($name) < 2 || mb_strlen($name) > 80) {
            $errors[] = 'Le nom doit contenir entre 2 et 80 caractères.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Adresse e-mail invalide.';
        }
        if ($phone !== '' && !preg_match('/^\+?\d{8,14}$/', $phone)) {
            $errors[] = 'Numéro de téléphone invalide.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }
        if ($errors) {
            return ['errors' => $errors, 'id' => null];
        }

        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetchColumn()) {
            return ['errors' => ['Un compte existe déjà avec cette adresse e-mail.'], 'id' => null];
        }

        $stmt = $this->db->prepare(
            'INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
        return ['errors' => [], 'id' => (int) $this->db->lastInsertId()];
    }

    public function authenticate(string $email, string $password): ?int
    {
        $stmt = $this->db->prepare('SELECT id, password_hash FROM users WHERE email = ?');
        $stmt->execute([strtolower(trim($email))]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            return (int) $user['id'];
        }
        return null;
    }
}
