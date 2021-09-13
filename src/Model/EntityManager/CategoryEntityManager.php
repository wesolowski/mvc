<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Database;

class CategoryEntityManager
{
    private \PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function insert(CategoryDataTransferObject $data): void
    {
        $query = $this->connection->prepare('INSERT INTO Category (CategoryName) VALUES (?)');
        $query->execute([$data->categoryname]);
    }

    public function update(CategoryDataTransferObject $data): void
    {
        $query = $this->connection->prepare('UPDATE Category SET CategoryName = ? WHERE CategoryID = ? LIMIT 1');
        $query->execute([$data->categoryname, $data->id]);
    }

    public function delete(int $id): void
    {
        $query = $this->connection->prepare('DELETE FROM Category WHERE CategoryID = ?');
        $query->execute([$id]);
    }
}