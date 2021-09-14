<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;

class CategoryProductEntityManager
{
    private \PDO $connection;

    public function __construct(Database $database)
    {
        $this->connection = $database->getConnection();
    }

    public function insert(int $categoryID, int $productID): void
    {
        $query = $this->connection->prepare('INSERT INTO CategoryProduct (CategoryID, ProductID) VALUES (?, ?)');
        $query->execute([$categoryID, $productID]);
    }

    public function delete(int $productID): void
    {
        $query = $this->connection->prepare('DELETE FROM CategoryProduct WHERE ProductID = ?');
        $query->execute([$productID]);
    }
}