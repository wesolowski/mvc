<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Core\Redirect;
use App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;
use App\Model\Database;
use phpDocumentor\Reflection\Types\Integer;

class ProductRepository
{
    private array $productDataTransferObjectListUsingName;
    private array $productDataTransferObjectListUsingID;
    private array $productDataTransferObjectListExcludeCategory;
    private Database $db;
    private array $category;
    private ProductMapper $productMapper;

    public function __construct(string $category, Database $db)
    {
        $this->db = $db;
        $this->category = explode('$', $category);
        $this->productMapper = new ProductMapper();

        $this->map();
    }

    public function map(): void
    {
        $this->productDataTransferObjectListUsingName = [];
        $this->productDataTransferObjectListUsingID = [];

        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID = ?');
        $query->execute(array($this->category[0]));

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectListUsingID[$mappedProduct->id] = $mappedProduct;
            $this->productDataTransferObjectListUsingName[$mappedProduct->productname] = $mappedProduct;
        }

        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID != ?');
        $query->execute(array($this->category[0]));

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectListExcludeCategory[$mappedProduct->id] = $mappedProduct;
        }
    }

    public function getNewID(): ?string
    {
        $id = $this->db->getConnection()->query('SELECT * FROM Product ORDER BY ProductID DESC LIMIT 1')->fetch(\PDO::FETCH_ASSOC)['ProductID'];
        return (string)$id;
    }

    public function getList(): array
    {
        return $this->productDataTransferObjectListUsingID;
    }

    public function getListExcludeCategory(): array
    {
        return $this->productDataTransferObjectListExcludeCategory;
    }

    public function getByID(string $id): ?ProductDataTransferObject
    {
        if ($this->hasProduct(['id' => $id]) === false) {
            return null;
        }

        return $this->productDataTransferObjectListUsingID[$id];
    }

    public function getByName(string $productname): ?ProductDataTransferObject
    {
        if ($this->hasProduct(['productname' => $productname]) === false) {
            return null;
        }

        return $this->productDataTransferObjectListUsingName[$productname];
    }

    public function hasProduct(array $check = []): bool
    {
        $isset = false;
        if (isset($check['id'])) {
            $isset = isset($this->productDataTransferObjectListUsingID[$check['id']]);
        } elseif (isset($check['productname'])) {
            $isset = isset($this->productDataTransferObjectListUsingName[$check['productname']]);
        }
        return $isset;
    }
}