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
    private Database $database;
    private int $categoryID;
    private ProductMapper $productMapper;

    public function __construct(Database $database, ProductMapper $productMapper)
    {
        $this->database = $database;
        $this->productMapper = $productMapper;
        if(isset($_GET['categoryID'])){
            $this->categoryID = (int)$_GET['categoryID'];
        } else {
            $this->categoryID = 0;
        }
    }

    public function getList(): array
    {
        $this->productDataTransferObjectList = [];
        $query = $this->database->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID = ?');
        $query->execute([$this->categoryID]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectList[$mappedProduct->id] = $mappedProduct;
        }

        return $this->productDataTransferObjectList;
    }

    public function getListExcludeCategory(): array
    {
        $this->productDataTransferObjectListExcludeCategory = [];
        $query = $this->database->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID != ?');
        $query->execute([$this->categoryID]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectListExcludeCategory[$mappedProduct->id] = $mappedProduct;
        }

        return $this->productDataTransferObjectListExcludeCategory;
    }

    public function getByID(int $id): ?ProductDataTransferObject
    {
        $mappedProduct = null;
        $query = $this->database->getConnection()->prepare('SELECT * FROM Product WHERE ProductID = ?');
        $query->execute([$id]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
        }
        return $mappedProduct;
    }

    public function getByName(string $productname): ?ProductDataTransferObject
    {
        $mappedProduct = null;
        $query = $this->database->getConnection()->prepare('SELECT * FROM Product WHERE ProductName = ?');
        $query->execute([$productname]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
        }
        return $mappedProduct;
    }
}