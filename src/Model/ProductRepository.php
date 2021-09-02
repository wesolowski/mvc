<?php
declare(strict_types=1);

namespace App\Model;

use App\Core\Redirect;
use \App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;


class ProductRepository
{
    private array $productDataTransferObjectList;

    public function __construct(string $category, Redirect $redirect)
    {
        if ($category === '' || !preg_match('/^[A-Z][a-z]*$/', $category)) {
            $redirect->redirect('index.php');
        }
        $path = file_get_contents(__DIR__ . "/../Model/" . $category . "Product.json");
        $list = json_decode($path, true); //true allowes accociative arrays
        if (json_last_error()) {
            exit("json error: " . json_last_error_msg() . " (" . json_last_error() . ")");
        }
        foreach ($list as $product) {
            $ProductMapper = new ProductMapper();
            $mappedProduct = $ProductMapper->map($product);
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