<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\ProductDataTransferObject;

class ProductMapper
{
    public function map(array $product): ProductDataTransferObject
    {
        $productDTO = new ProductDataTransferObject();
        $productDTO->id = 0;
        $productDTO->price = 0.00;
        $productDTO->categoryId = 0;

        if(isset($product['id'])){
            $productDTO->id = (int)$product['id'];
        }

        $productDTO->name = $product['name'] ?? '';

        if(isset($product['price'])) {
            $productDTO->price = (float)$product['price'];
        }

        $productDTO->description = $product['description'] ?? null;

        if(isset($product['categoryId'])){
            $productDTO->categoryId = (int)$product['categoryId'];
        }

        return $productDTO;
    }
}