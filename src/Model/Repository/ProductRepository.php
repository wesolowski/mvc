<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;
use App\Model\Database;

class ProductRepository
{
    private array $productDataTransferObjectList;
    private array $productDataTransferObjectListExcludeCategory;
    private Database $db;
    private array $categoryId;
    private ProductMapper $productMapper;

    public function __construct(string $categoryId, Database $db)
    {
        $this->db = $db;
        $this->categoryId = $categoryId;
        $this->productMapper = new ProductMapper();
    }

    public function getNewID(): ?string
    {
        $id = $this->db->getConnection()->query('SELECT * FROM Product ORDER BY ProductID DESC LIMIT 1')->fetch(\PDO::FETCH_ASSOC)['ProductID'];
        return (string)$id;
    }

    public function getList(): array
    {
        $this->productDataTransferObjectList = [];
        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID = ?');
        $query->execute([$this->categoryId]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectList[$mappedProduct->id] = $mappedProduct;
        }

        return $this->productDataTransferObjectList;
    }

    public function getListExcludeCategory(): array
    {
        $this->productDataTransferObjectListExcludeCategory = [];
        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID != ?');
        $query->execute([$this->categoryId]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectListExcludeCategory[$mappedProduct->id] = $mappedProduct;
        }

        return $this->productDataTransferObjectListExcludeCategory;
    }

    public function getByID(int $id): ProductDataTransferObject
    {
        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE p.ProductID = ?');
        $query->execute([$id]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
        }
        return $mappedProduct;
    }

    public function getByName(string $productname): ProductDataTransferObject
    {
        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE p.ProductName = ?');
        $query->execute([$productname]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
        }
        return $mappedProduct;
    }
}