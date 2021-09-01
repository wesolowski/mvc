<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\CategoryDataTransferObject;

class CategoryMapper
{
    public function map(array $category): CategoryDataTransferObject
    {
        $categoryDTO = new CategoryDataTransferObject();

        $categoryDTO->id = $category['id'] ?? '';
        $categoryDTO->categoryname = $category['categoryname'] ?? '';

        return $categoryDTO;
    }
}