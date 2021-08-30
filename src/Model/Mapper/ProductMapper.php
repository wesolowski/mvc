<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\ProductDataTransferObject;

class ProductMapper
{
    public function map(array $product): ProductDataTransferObject
    {
        $productDTO = new ProductDataTransferObject();

        $productDTO->id = $product['id'] ?? '';
        $productDTO->productname = $product['productname'] ?? '';
        $productDTO->description = $product['description'] ?? '';

        return $productDTO;
    }
}