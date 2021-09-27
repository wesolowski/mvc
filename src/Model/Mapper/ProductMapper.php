<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\ProductDataTransferObject;

class ProductMapper
{
    public function map(array $product): ProductDataTransferObject
    {
        $productDTO = new ProductDataTransferObject();

        if(isset($product['ProductID'])){
            $productDTO->id = (int)$product['ProductID'];
        } else {
            $productDTO->id = 0;
        }
        $productDTO->name = $product['ProductName'] ?? '';
        $productDTO->description = $product['ProductDescription'] ?? '';
        if(isset($product['CategoryID'])){
            $productDTO->categoryID = (int)$product['CategoryID'];
        } else {
            $productDTO->categoryID = 0;
        }


        return $productDTO;
    }
}