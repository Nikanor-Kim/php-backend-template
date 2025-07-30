<?php
namespace App\Services;

use App\Utils\Logger;
use PDO;
use PDOException;

class DbService
{
    // 🔐 Приватное статическое свойство для хранения PDO
    private static ?PDO $pdo = null;

    public static function init(): void
    {
        self::getPdo(); // просто вызывает ленивую инициализацию
    }
    // ⚙️ Приватный метод, который создаёт PDO, если он ещё не создан
    public static function getPdo(): PDO
    {
        if (self::$pdo === null) {
            try {
                // Подключение через .env (или зашито напрямую)
                $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";
                // $dsn = "mysql:host=localhost;dbname={$_ENV['DB_NAME']};charset=utf8mb4";

                self::$pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                // Logger::error('Ошибка подключения к БД: ' . $e->getMessage());
                throw new \Exception('Ошибка подключения к БД: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
    public static function example(array $taskData): bool
    {
        try {
            // code
            return true;
        } catch (PDOException $e) {

            if (self::getPdo()->inTransaction()) {
                self::getPdo()->rollBack();
            }
            throw new PDOException("Ошибка при добавлении в очередь: " . $e->getMessage());
        }
    }
}