<?php

declare(strict_types=1);

final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = self::connect(env('DB_DSN', 'sqlite:storage/ticketgo.sqlite'));
        }
        return self::$pdo;
    }

    public static function connect(string $dsn): PDO
    {
        if (str_starts_with($dsn, 'sqlite:') && !str_starts_with($dsn, 'sqlite::memory:')) {
            $file = substr($dsn, 7);
            if ($file[0] !== '/') {
                $dsn = 'sqlite:' . ROOT_PATH . '/' . $file;
            }
        }
        $pdo = new PDO($dsn, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        if ($pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite') {
            $pdo->exec('PRAGMA foreign_keys = ON');
        }
        return $pdo;
    }
}
