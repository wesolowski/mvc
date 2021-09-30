<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Database;
//TODO change to ORM
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
        $this->deleteCategoryProductByCategoryId($id);

        $query = $this->connection->prepare('DELETE FROM category WHERE id = ? LIMIT 1');
        $query->execute([$id]);
    }

    /**
     * @param int $categoryId
     */
    private function deleteCategoryProductByCategoryId(int $categoryId): void
    {
        $query = $this->connection->prepare('DELETE FROM categoryProduct WHERE categoryId = ? LIMIT 1');

        $query->execute([$categoryId]);
    }
}
