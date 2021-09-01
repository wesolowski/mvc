<?php
declare(strict_types=1);

namespace App\Model;

use \App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;


class ProductRepository
{
    private array $productDataTransferObjectList;

    public function __construct()
    {
        $path = file_get_contents(__DIR__ . "/../Model/Product.json");
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
        //$test1 = $this->productDataTransferObjectList; array dann productTransferObject
        //$test2 = $this->list; array dann array bei smarty ändern . zu ->
        return $this->productDataTransferObjectList;
    }

    public function getProduct(string $id): ProductDataTransferObject
    {
        if($this->hasProduct($id) === false) {
            throw new \RuntimeException("Product not found");
        }

        return $this->productDataTransferObjectList[$id];
    }

    public function hasProduct(string $id): bool
    {
        return isset($this->productDataTransferObjectList[$id]);
    }
}