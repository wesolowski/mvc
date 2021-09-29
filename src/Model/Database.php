<?php
declare(strict_types=1);

namespace App\Model;

use RuntimeException;

class Database
{
    private array $dbSettings;
    private \PDO $connection;

    public function __construct($settings = [])
    {
        $this->dbSettings['server'] = $settings['server'] ?? "localhost";
        $this->dbSettings['user'] = $settings['user'] ?? "philipp";
        $this->dbSettings['password'] = $settings['password'] ?? "nexusval";
        $this->dbSettings['database'] = $settings['database'] ?? "mvc";
        $this->dbSettings['charset'] = $settings['charset'] ?? "utf8mb4";
    }

    public function __destruct()
    {
        if (isset($this->connection)) {
            unset($this->connection);
        }
    }

    public function getSettings(): array
    {
        return $this->dbSettings;
    }

    public function connect(): void
    {
        try {
            $dsn = "mysql:host=" . $this->dbSettings['server'] .
                ";dbname=" . $this->dbSettings['database'] .
                ";charset=" . $this->dbSettings['charset'];
            $this->connection = new \PDO($dsn, $this->dbSettings['user'], $this->dbSettings['password']);
        } catch (\PDOException $error) {
            throw new \PDOException("Connection failed: " . $error->getMessage());
        }
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}