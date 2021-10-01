<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;
use Doctrine\ORM\EntityManager;

class CategoryRepository
{
    private EntityManager $entityManager;
    private CategoryMapper $categoryMapper;

    public function __construct(EntityManager $entityManager, CategoryMapper $categoryMapper)
    {
        $this->entityManager = $entityManager;
        $this->categoryMapper = $categoryMapper;
    }

    /**
     * @return CategoryDataTransferObject[]
     */
    public function getList(): array
    {
        $categoryDTOList = [];

        $categories = $this->entityManager->getRepository('\App\Model\ORMEntity\Category')->findAll();

        foreach ($categories as $category) {
            $categoryDTO = $this->categoryMapper->map($category);
            $categoryDTOList[$categoryDTO->id] = $categoryDTO;
        }

        return $categoryDTOList;
    }

    public function getById(int $id): ?CategoryDataTransferObject
    {
        $category = $this->entityManager->getRepository('\App\Model\ORMEntity\Category')
                                        ->findBy(array('id' => $id));

        if (empty($category)) {
            return null;
        }

        return $this->categoryMapper->map($category);
    }

    public function getByName(string $name): ?CategoryDataTransferObject
    {
        $category = $this->entityManager->getRepository('App/Model/ORMEntity/Category')
            ->findBy(array('name' => $name));

        if (empty($category)) {
            return null;
        }

        return $this->categoryMapper->map($category);
    }
}