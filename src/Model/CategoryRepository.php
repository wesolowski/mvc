<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;

class CategoryRepository
{
    private array $categoryDataTransferObjectList;

    public function __construct()
    {
        global $db;
        $categoryQuery = $db->getConnection()->query("SELECT * FROM Category ORDER BY CategoryID");
        while ($category = $categoryQuery->fetch(\PDO::FETCH_ASSOC)) {
            $categoryMapper = new CategoryMapper();
            $mappedCategory = $categoryMapper->map($category);
            $this->categoryDataTransferObjectList[$mappedCategory->id] = $mappedCategory;
        }
    }

    public function getList(): array
    {
        return $this->categoryDataTransferObjectList;
    }

    public function getById(string $id): CategoryDataTransferObject
    {
        if ($this->hasCategory($id) === false) {
            throw new \RuntimeException("Category not found");
        }
        return $this->categoryDataTransferObjectList[$id];
    }

    public function hasCategory(string $id): bool
    {
        return isset($this->categoryDataTransferObjectList[$id]);
    }
}