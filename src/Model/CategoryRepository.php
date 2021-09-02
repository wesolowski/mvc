<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;

class CategoryRepository
{
    //TODO fix Category Repository core dump problem starts here!
    private array $categoryDataTransferObjectList;

    public function __construct()
    {
        $path = file_get_contents(__DIR__ . "/../Model/Category.json");
        $list = json_decode($path, true);
        if (json_last_error()) {
            exit("json error: " . json_last_error_msg() . " (" . json_last_error() . ")");
        }
        foreach ($list as $category) {
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