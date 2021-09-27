<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Dto\UserDataTransferObject;

class UserEntityManager
{
    private \PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function insert(UserDataTransferObject $userDTO): void
    {
        $query = $this->connection->prepare('INSERT INTO user (name, password) VALUES (?, ?)');
        $query->execute([$userDTO->name, $userDTO->password]);
    }

    public function update(UserDataTransferObject $userDTO): void
    {
        $query = $this->connection->prepare('UPDATE user SET name = ?, password = ? WHERE id = ? LIMIT 1');
        $query->execute([$userDTO->name, $userDTO->password, $userDTO->id]);
    }

    public function delete(int $id): void
    {
        $query = $this->connection->prepare('DELETE FROM user WHERE id = ?');
        $query->execute([$id]);
    }
}