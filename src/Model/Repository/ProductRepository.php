<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;
use App\Model\Database;

class ProductRepository
{
    private Database $database;
    private int $categoryId;
    private ProductMapper $productMapper;

    public function __construct(Database $database, ProductMapper $productMapper)
    {
        $this->database = $database;
        $this->productMapper = $productMapper;
    }

    private function getCategoryID(): void
    {
        $this->categoryId = 0;
        if(isset($_GET['categoryId'])){
            $this->categoryId = (int)$_GET['categoryId'];
        }
    }

    /**
     * @return ProductDataTransferObject[]
     */
    public function getList(): array
    {
        $this->getCategoryID();
        $productDTOList = [];

        $query = $this->database->getConnection()->prepare('SELECT * FROM product p JOIN categoryProduct cp ON p.id = cp.productId WHERE cp.categoryId = ?');
        $query->execute([$this->categoryId]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $productDTO = $this->productMapper->map($product);
            $productDTOList[$productDTO->id] = $productDTO;
        }

        return $productDTOList;
    }

    /**
     * @return ProductDataTransferObject[]
     */
    public function getListExcludeCategory(): array
    {
        $this->getCategoryID();
        $productDTOListExcludeCategory = [];

        $query = $this->database->getConnection()->prepare('SELECT * FROM product p JOIN categoryProduct cp ON p.id = cp.productId WHERE cp.categoryId != ?');
        $query->execute([$this->categoryId]);

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $productDTO = $this->productMapper->map($product);
            $productDTOListExcludeCategory[$productDTO->id] = $productDTO;
        }

        return $productDTOListExcludeCategory;
    }

    public function getByID(int $id): ?ProductDataTransferObject
    {
        $query = $this->database->getConnection()->prepare('SELECT * FROM product WHERE id = ? LIMIT 1');
        $query->execute([$id]);

        $product = $query->fetch(\PDO::FETCH_ASSOC);

        if(empty($product)) {
            return null;
        }

        return $this->productMapper->map($product);
    }

    public function getByName(string $name): ?ProductDataTransferObject
    {
        $query = $this->database->getConnection()->prepare('SELECT * FROM product WHERE name = ? LIMIT 1');
        $query->execute([$name]);

        $product = $query->fetch(\PDO::FETCH_ASSOC);

        if(empty($product)) {
            return null;
        }

        return $this->productMapper->map($product);
    }
}