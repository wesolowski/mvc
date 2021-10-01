<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\ORMEntity\CategoryProduct;
use Doctrine\ORM\EntityManager;

class CategoryProductEntityManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insert(int $categoryID, int $productID): void
    {
        $categoryProduct = new CategoryProduct();
        $categoryProduct->setCategoryId($categoryID);
        $categoryProduct->setProductId($productID);

        $this->entityManager->persist($categoryProduct);
        $this->entityManager->flush();
    }

    public function delete(int $categoryID, int $productID): void
    {
        $categoryProduct = $this->entityManager->getRepository('CategoryProduct')
            ->findBy(['categoryId' => $categoryID, 'productId' => $productID]);

        $this->entityManager->remove($categoryProduct);
        $this->entityManager->flush();
    }
}
