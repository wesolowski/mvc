<?php
declare(strict_types=1);

namespace App\Model\Dto;

class ProductDataTransferObject
{
    public int $id;
    public string $productname;
    public string $description;
    public array $categoryID;
}