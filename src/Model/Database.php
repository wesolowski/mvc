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
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $error) {
            throw new \PDOException("Connection failed: " . $error->getMessage());
        }
    }

    public function disconnect(): void
    {
        if (isset($this->connection)) {
            unset($this->connection);
        } else {
            throw new RuntimeException("Cant Disconnect, no Database connection found!");
        }
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}