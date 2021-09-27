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
        $queryProduct = $this->connection->prepare('INSERT INTO product (name, price, description) VALUES (?, ?, ?)');
        $queryProduct->execute([$productDTO->name, $productDTO->price, $productDTO->description]);

        $newProductDTOId = $this->productRepository->getByName($productDTO->name)->id;

        $queryCategoryProduct = $this->connection->prepare('INSERT INTO categoryProduct (categoryId, productId) VALUES (?, ?)');
        $queryCategoryProduct->execute([$productDTO->categoryId, $newProductDTOId]);
    }

    public function update(ProductDataTransferObject $productDTO): void
    {
        $query = $this->connection->prepare('UPDATE product SET name = ?, price = ?, description = ? WHERE id = ? LIMIT 1');
        $query->execute([$productDTO->name, $productDTO->price, $productDTO->description, $productDTO->id]);
    }

    public function delete(int $id): void
    {
        $queryCategoryProduct = $this->connection->prepare('DELETE FROM categoryProduct WHERE id = ?');
        $queryCategoryProduct->execute([$id]);

        $queryProduct = $this->connection->prepare('DELETE FROM product WHERE id = ? LIMIT 1');
        $queryProduct->execute([$id]);
    }
}