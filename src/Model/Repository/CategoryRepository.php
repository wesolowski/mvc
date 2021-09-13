<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;
use App\Model\Database;

class CategoryRepository
{
    private Database $db;
    private CategoryMapper $categoryMapper;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->categoryMapper = new CategoryMapper();
    }

    public function getList(): array
    {
        $categoryDataTransferObjectList = [];
        $query = $this->db->getConnection()->query("SELECT * FROM Category");
        while ($category = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedCategory = $this->categoryMapper->map($category);
            $categoryDataTransferObjectList[$mappedCategory->id] = $mappedCategory;
        }
        return $categoryDataTransferObjectList;
    }

    public function getById(int $id): ?CategoryDataTransferObject
    {
        $mappedCategory = null;
        $query = $this->db->getConnection()->prepare("SELECT * FROM Category WHERE CategoryID = ?");
        $query->execute([$id]);
        while ($category = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedCategory = $this->categoryMapper->map($category);
        }
        return $mappedCategory;
    }

    public function getByName(string $name): ?CategoryDataTransferObject
    {
        $mappedCategory = null;
        $query = $this->db->getConnection()->prepare("SELECT * FROM Category WHERE CategoryName = ?");
        $query->execute([$name]);
        while ($category = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedCategory = $this->categoryMapper->map($category);
        }
        return $mappedCategory;
    }
}