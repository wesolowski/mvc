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

    public function insert(CategoryDataTransferObject $categoryDTO): void
    {
        $query = $this->connection->prepare('INSERT INTO Product (CategoryName) VALUES (?)');
        $query->execute([$categoryDTO->categoryname]);
    }

    public function update(CategoryDataTransferObject $categoryDTO): void
    {
        $query = $this->connection->prepare('UPDATE Product SET CategoryName = ? WHERE CategoryID = ? LIMIT 1');
        $query->execute([$categoryDTO->categoryname, $categoryDTO->id]);
    }

    public function delete(int $id): void
    {
        $query = $this->connection->prepare('DELETE FROM Product WHERE CategoryID = ?');
        $query->execute([$id]);
    }
}