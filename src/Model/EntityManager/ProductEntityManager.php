<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Dto\ProductDataTransferObject;
use App\Model\ORMEntity\CategoryProduct;
use App\Model\ORMEntity\Product;
use Doctrine\ORM\EntityManager;

class ProductEntityManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insert(ProductDataTransferObject $productDTO, int $categoryID): void
    {
        $product = new Product();
        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setDescription($productDTO->description);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $categoryProduct = new CategoryProduct();
        $categoryProduct->setProductId($product->getId());
        $categoryProduct->setCategoryId($categoryID);

        $this->entityManager->persist($categoryProduct);
        $this->entityManager->flush();
    }

    public function update(ProductDataTransferObject $productDTO): void
    {
        $product = $this->entityManager->getReference('Product', $productDTO->id);

        $product->setName($productDTO->name);
        $product->setPrice($productDTO->price);
        $product->setDescription($productDTO->description);

        $this->entityManager->flush();
    }

    public function delete(int $categoryId): void
    {
        $categoryProduct = $this->entityManager->getRepository('CategoryProduct')
            ->findBy(['productId' => $categoryId]);

        $this->entityManager->remove($categoryProduct);
        $this->entityManager->flush();

        $product = $this->entityManager->getReference('Product', $categoryId);

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
