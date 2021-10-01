<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Database;
use App\Model\ORMEntity\Category;
use Doctrine\ORM\EntityManager;

//TODO change to ORM
class CategoryEntityManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insert(CategoryDataTransferObject $categoryDTO): void
    {
        $category = new Category();
        $category->setName($categoryDTO->name);

        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function update(CategoryDataTransferObject $categoryDTO): void
    {
        $category = $this->entityManager->getReference('Category', $categoryDTO->id);
        $category->setName($categoryDTO->name);

        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $category = $this->entityManager->getReference('Category', $id);
        $this->entityManager->remove($category);

        $this->entityManager->flush();
    }
}
