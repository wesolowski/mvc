<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\CategoryDataTransferObject;

class CategoryMapper
{
    public function map(array $category): CategoryDataTransferObject
    {
        $categoryDTO = new CategoryDataTransferObject();

        if(isset($category['id'])){
            $categoryDTO->id = (int)$category['id'];
        } else {
            $categoryDTO->id = 0;
        }
        $categoryDTO->name = $category['name'] ?? '';

        return $categoryDTO;
    }
}