<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Dto\ProductDataTransferObject;
use App\Model\Mapper\ProductMapper;
use Doctrine\ORM\EntityManager;

class ProductRepository
{
    private EntityManager $entityManager;
    private int $categoryId = 0;
    private ProductMapper $productMapper;

    public function __construct(EntityManager $entityManager, ProductMapper $productMapper)
    {
        $this->entityManager = $entityManager;
        $this->productMapper = $productMapper;
    }

    private function getCategoryID(): void
    {
        if (isset($_GET['categoryId'])) {
            $this->categoryId = (int)$_GET['categoryId'];
        }
    }

    /**
     * @return ProductDataTransferObject[]
     */
    public function getList(): array
    {
        $this->getCategoryID();
        $productDTOList = [];

        $products = $this->entityManager->createQuery("SELECT p.id, p.name, p.price, p.description FROM App/Model/ORMEntityManager/Product p JOIN App/Model/ORMEntityManager/CategoryProduct cp ON p.id = cp.productId WHERE cp.categoryId = ?1")
                                     ->setParameter(1, $this->categoryId)
                                     ->getResult();

        foreach ($products as $product){
            $productDTO = $this->productMapper->map($product);
            $productDTOList[$productDTO->id] = $productDTO;
        }

        return $productDTOList;
    }

    /**
     * @return ProductDataTransferObject[]
     */
    public function getListExcludeCategory(): array
    {
        $this->getCategoryID();
        $productDTOListExcludeCategory = [];

        $products = $this->entityManager->createQuery("SELECT p.id, p.name, p.price, p.description FROM App/Model/ORMEntityManager/Product p JOIN App/Model/ORMEntityManager/CategoryProduct cp ON p.id = cp.productId WHERE cp.categoryId != ?1")
            ->setParameter(1, $this->categoryId)
            ->getResult();

        foreach ($products as $product){
            $productDTO = $this->productMapper->map($product);
            $productDTOListExcludeCategory[$productDTO->id] = $productDTO;
        }

        return $productDTOListExcludeCategory;
    }

    public function getByID(int $id): ?ProductDataTransferObject
    {
        $product = $this->entityManager->getRepository('App/Model/ORMEntityManager/Product')
                                       ->find(array('id' => $id));

        if (empty($product)) {
            return null;
        }

        return $this->productMapper->map($product);
    }

    public function getByName(string $name): ?ProductDataTransferObject
    {
        $product = $this->entityManager->getRepository('App/Model/ORMEntityManager/Product')
            ->find(array('name' => $name));

        if (empty($product)) {
            return null;
        }

        return $this->productMapper->map($product);
    }
}