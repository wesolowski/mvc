<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Core\Redirect;
use App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;
use App\Model\Database;

class ProductRepository
{
    private array $productDataTransferObjectList;
    private Database $db;
    private array $category;
    private ProductMapper $productMapper;

    public function __construct(string $category, Redirect $redirect, Database $db)
    {
        $this->db = $db;
        $this->category = explode('$', $category);
        $this->productMapper = new ProductMapper();

        $this->map();

        if (empty($this->productDataTransferObjectList)) {
            $redirect->redirect('index.php');
        }
    }

    public function map(): void
    {
        $query = $this->db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID = ?');
        $query->execute(array($this->category[0]));

        while ($product = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedProduct = $this->productMapper->map($product);
            $this->productDataTransferObjectList[$mappedProduct->id] = $mappedProduct;
        }
    }

    public function getList(): array
    {
        return $this->productDataTransferObjectList;
    }

    public function getProduct(string $id): ProductDataTransferObject
    {
        if ($this->hasProduct($id) === false) {
            throw new \RuntimeException("Product not found");
        }

        return $this->productDataTransferObjectList[$id];
    }

    public function hasProduct(string $id): bool
    {
        return isset($this->productDataTransferObjectList[$id]);
    }
}