<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;
use App\Model\Database;

class CategoryRepository
{
    private Database $database;
    private CategoryMapper $categoryMapper;

    public function __construct(Database $database, CategoryMapper $categoryMapper)
    {
        $this->database = $database;
        $this->categoryMapper = $categoryMapper;
    }

    /**
     * @return CategoryDataTransferObject[]
     */
    public function getList(): array
    {
        $categoryDTOList = [];

        $query = $this->database->getConnection()->query("SELECT * FROM category");

        while ($category = $query->fetch(\PDO::FETCH_ASSOC)) {
            $categoryDTO = $this->categoryMapper->map($category);
            $categoryDTOList[$categoryDTO->id] = $categoryDTO;
        }
        return $categoryDTOList;
    }

    public function getById(int $id): ?CategoryDataTransferObject
    {
        $query = $this->database->getConnection()->prepare("SELECT * FROM category WHERE id = ? LIMIT 1");
        $query->execute([$id]);
        $category = $query->fetch(\PDO::FETCH_ASSOC);

        if (empty($category)) {
            return null;
        }

        return $this->categoryMapper->map($category);
    }

    public function getByName(string $name): ?CategoryDataTransferObject
    {
        $query = $this->database->getConnection()->prepare("SELECT * FROM category WHERE name = ? LIMIT 1");
        $query->execute([$name]);
        $category = $query->fetch(\PDO::FETCH_ASSOC);

        if(empty($category)) {
            return null;
        }

        return $this->categoryMapper->map($category);
    }
}