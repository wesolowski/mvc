<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\ProductDataTransferObject;

class ProductMapper
{
    public function map(array $product): ProductDataTransferObject
    {
        $productDTO = new ProductDataTransferObject();

        $productDTO->id = $product['ProductID'] ?? 0;
        $productDTO->productname = $product['ProductName'] ?? '';
        $productDTO->description = $product['ProductDescription'] ?? null;
        $productDTO->categoryID = $product['CategoryID'] ?? null;

        return $productDTO;
    }
}