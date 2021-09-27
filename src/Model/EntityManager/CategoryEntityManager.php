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
        $query = $this->connection->prepare('INSERT INTO category (name) VALUES (?)');
        $query->execute([$categoryDTO->name]);
    }

    public function update(CategoryDataTransferObject $categoryDTO): void
    {
        $query = $this->connection->prepare('UPDATE category SET name = ? WHERE id = ? LIMIT 1');
        $query->execute([$categoryDTO->name, $categoryDTO->id]);
    }

    public function delete(int $id): void
    {
        $query = $this->connection->prepare('DELETE FROM category WHERE id = ? LIMIT 1');
        $query->execute([$id]);
    }
}