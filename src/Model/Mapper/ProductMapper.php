<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\ProductDataTransferObject;

class ProductMapper
{
    public function map(array $product): ProductDataTransferObject
    {
        $productDTO = new ProductDataTransferObject();

        if(isset($product['id'])){
            $productDTO->id = (int)$product['id'];
        } else {
            $productDTO->id = 0;
        }
        $productDTO->name = $product['name'] ?? '';
        $productDTO->description = $product['description'] ?? '';
        if(isset($product['categoryId'])){
            $productDTO->categoryID = (int)$product['categoryId'];
        } else {
            $productDTO->categoryID = 0;
        }


        return $productDTO;
    }
}