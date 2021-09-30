<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\ProductDataTransferObject;
use App\Model\ORMEntityManager\Product;

class ProductMapper
{
    public function map(Product $product): ProductDataTransferObject
    {
        $productDTO = new ProductDataTransferObject();

        $productDTO->id = $product->getId();

        $productDTO->name = $product->getName();

        $productDTO->price = $product->getPrice();

        $productDTO->description = $product->getDescription();

        return $productDTO;
    }
}