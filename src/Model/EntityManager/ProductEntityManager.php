<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class ProductEntityManager implements EntityManagerInterface
{
    private \PDO $connection;
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(Database $database, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->connection = $database->getConnection();
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function insert(array $data = []): ?string
    {
        $returnMessage = null;
        if (isset($data['productname'], $data['categoryID']) && $data['productname'] !== '' && $data['categoryID'] !== '') {
            if ($this->productRepository->hasProduct(['productname' => $data['productname']]) === false && $this->categoryRepository->hasCategory(['id' => $data['categoryID']]) === true) {
                $description = $data['description'] ?? null;

                $queryProduct = $this->connection->prepare('INSERT INTO Product (ProductName, ProductDescription) VALUES (?, ?)');
                $queryCategoryProduct = $this->connection->prepare('INSERT INTO CategoryProduct (CategoryID, ProductID) VALUES (?, ?)');

                $queryProduct->execute([$data['productname'], $description]);

                $newProduct = $this->productRepository->getNewID();

                $queryCategoryProduct->execute([$data['categoryID'], $newProduct]); //TODO FIX THIS

                $this->productRepository->map();
            } else {
                $returnMessage = "Product already exists or/and Category does not exist";
            }
        } else {
            $returnMessage = "Productname and Category ID musst be given";
        }
        return $returnMessage;
    }

    public function update(array $data = []): ?string
    {
        $returnMessage = null;
        if (isset($data['productname'], $data['id']) && $data['productname'] !== '' && $data['id'] !== '') {
            if ($this->productRepository->hasProduct(['id' => $data['id']]) === true) {
                $description = $data['description'] ?? null;

                $query = $this->connection->prepare('UPDATE Product SET ProductName = ?, ProductDescription = ? WHERE ProductID = ?');
                $query->execute([$data['productname'], $description, $data['id']]);

                $this->productRepository->map();
            } else {
                $returnMessage = "Product doesnt exist";
            }
        } else {
            $returnMessage = "Productname and ID musst be given";
        }
        return $returnMessage;
    }

    public function delete(array $data  = []): ?string
    {
        $returnMessage = null;
        if(isset($data['id']) && $data['id'] !== ''){
            $queryProduct = $this->connection->prepare('DELETE FROM Product WHERE ProductID = ? LIMIT 1');
            $queryCategoryProduct = $this->connection->prepare('DELETE FROM CategoryProduct WHERE ProductID = ? LIMIT 1');
            $queryCategoryProduct->execute([$data['id']]);
            $queryProduct->execute([$data['id']]);
            $this->productRepository->map();
        } else {
            $returnMessage = "Id musst be given";
        }
        return $returnMessage;
    }
}