<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Dto\ProductDataTransferObject;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class ProductEntityManager
{
    private \PDO $connection;
    private ProductRepository $productRepository;

    public function __construct(Database $database, ProductRepository $productRepository)
    {
        $this->connection = $database->getConnection();
        $this->productRepository = $productRepository;
    }

    public function insert(ProductDataTransferObject $productDTO): void
    {
        $queryProduct = $this->connection->prepare('INSERT INTO Product (ProductName, ProductDescription) VALUES (?, ?)');
        $queryProduct->execute([$productDTO->productname, $productDTO->description]);

        $newProductId = $this->productRepository->getByName($productDTO->productname)->id;

        $queryCategoryProduct = $this->connection->prepare('INSERT INTO CategoryProduct (CategoryID, ProductID) VALUES (?, ?)');
        $queryCategoryProduct->execute([$productDTO->categoryID, $newProductId]);
    }

    public function update(ProductDataTransferObject $productDTO): void
    {
        $query = $this->connection->prepare('UPDATE Product SET ProductName = ?, ProductDescription = ? WHERE ProductID = ?');
        $query->execute([$productDTO->productname, $productDTO->description, $productDTO->id]);
    }

    public function delete(int $id): void
    {
        $queryCategoryProduct = $this->connection->prepare('DELETE FROM CategoryProduct WHERE ProductID = ? LIMIT 1');
        $queryCategoryProduct->execute([$id]);
        $queryProduct = $this->connection->prepare('DELETE FROM Product WHERE ProductID = ? LIMIT 1');
        $queryProduct->execute([$id]);
    }
}