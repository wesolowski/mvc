<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\CategoryDataTransferObject;

class CategoryMapper
{
    public function map(array $category = []): CategoryDataTransferObject
    {
        $categoryDTO = new CategoryDataTransferObject();

        if(isset($category['CategoryID'])){
            $categoryDTO->id = (int)$category['CategoryID'];
        } else {
            $categoryDTO->id = 0;
        }
        $categoryDTO->categoryname = $category['CategoryName'] ?? '';

        return $categoryDTO;
    }
}