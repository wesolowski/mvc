<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\ORMEntityManager\Category;

class CategoryMapper
{
    public function map(Category $category): CategoryDataTransferObject
    {
        $categoryDTO = new CategoryDataTransferObject();

        $categoryDTO->id = $category->getId();

        $categoryDTO->name = $category->getName();

        return $categoryDTO;
    }
}