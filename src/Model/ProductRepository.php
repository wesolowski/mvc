<?php
declare(strict_types=1);

namespace App\Model;

use \App\Model\Dto\ProductDataTransferObject;

class ProductMapper
{
    public function map(array $product): ProductDataTransferObject
    {
        $productDataTransferObject = new ProductDataTransferObject($product);
        return $productDataTransferObject;
    }
}

class ProductRepository
{
    private array $list;

    public function __construct()
    {
        $path = file_get_contents(__DIR__ . "/../Model/Product.json");
        $this->list = json_decode($path, true); //true allowes accociative arrays
        if (json_last_error()) {
            exit("json error: " . json_last_error_msg() . " (" . json_last_error() . ")");
        }
        $productMapper = new ProductMapper();
        $productMapper->map($this->list);
    }

    public function getList(): array
    {
        return $this->list;
    }

    public function getProduct(int $id): mixed
    {
        $list = $this->list;
        foreach ($list as $product) {
            if ((int)$product['id'] === $id) {
                return $product;
            }
        }
        return false;
    }

    public function hasProduct(string $product_name)
    {
        $list = $this->getList();
        foreach ($list as $key => $value) {
            if ($product_name === $value['productname']) {
                return "Product found!";
            }
        }
    }
}