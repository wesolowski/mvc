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
        $query = $this->connection->prepare('INSERT INTO User (Username, Password) VALUES (?, ?)');
        $query->execute([$userDTO->username, $userDTO->password]);
    }

    public function update(UserDataTransferObject $userDTO): void
    {
        $query = $this->connection->prepare('UPDATE User SET Username = ?, Password = ? WHERE UserID = ? LIMIT 1');
        $query->execute([$userDTO->username, $userDTO->password, $userDTO->id]);
    }

    public function delete(int $id): void
    {
        $query = $this->connection->prepare('DELETE FROM User WHERE UserID = ?');
        $query->execute([$id]);
    }
}