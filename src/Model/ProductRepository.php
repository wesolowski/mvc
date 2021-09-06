<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Redirect;
use \App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;


class ProductRepository
{
    private array $productDataTransferObjectList;

    public function __construct(string $category, Redirect $redirect, Database $db)
    {
        $category = explode('$', $category);

        $preProductQuery = $db->getConnection()->prepare('SELECT * FROM Product p JOIN CategoryProduct cp ON p.ProductID = cp.ProductID WHERE cp.CategoryID = ?');
        $preProductQuery->execute(array($category[0]));

        while ($product = $preProductQuery->fetch(\PDO::FETCH_ASSOC)) {
            $productMapper = new ProductMapper();
            $mappedProduct = $productMapper->map($product);
            $this->productDataTransferObjectList[$mappedProduct->id] = $mappedProduct;
        }

        if (empty($this->productDataTransferObjectList)) {
            $redirect->redirect('index.php');
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