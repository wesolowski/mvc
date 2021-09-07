<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;
use App\Model\Database;

class CategoryRepository
{
    private array $categoryDataTransferObjectListUsingID;
    private array $categoryDataTransferObjectListUsingName;
    private Database $db;
    private CategoryMapper $categoryMapper;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->categoryMapper = new CategoryMapper();
        $this->map();
    }

    public function map(): void
    {
        $categoryQuery = $this->db->getConnection()->query("SELECT * FROM Category ORDER BY CategoryName");
        while ($category = $categoryQuery->fetch(\PDO::FETCH_ASSOC)) {
            $mappedCategory = $this->categoryMapper->map($category);
            $this->categoryDataTransferObjectListUsingID[$mappedCategory->id] = $mappedCategory;
            $this->categoryDataTransferObjectListUsingName[$mappedCategory->categoryname] = $mappedCategory;
        }
    }

    public function getList(): array
    {
        return $this->categoryDataTransferObjectListUsingID;
    }

    public function getById(string $id): CategoryDataTransferObject
    {
        if ($this->hasCategory(['id' => $id]) === false) {
            throw new \RuntimeException("Category not found");
        }
        return $this->categoryDataTransferObjectListUsingID[$id];
    }

    public function getByName(string $name): CategoryDataTransferObject
    {
        if ($this->hasCategory(['categoryname' => $name]) === false) {
            throw new \RuntimeException("Category not found");
        }
        return $this->categoryDataTransferObjectListUsingName[$name];
    }

    public function hasCategory(array $check = []): bool
    {
        $isset = false;
        if (isset($check['categoryname'])) {
            $isset = isset($this->categoryDataTransferObjectListUsingName[$check['categoryname']]);
        } elseif (isset($check['id'])) {
            $isset = isset($this->categoryDataTransferObjectListUsingID[$check['id']]);
        }
        return $isset;
    }
}