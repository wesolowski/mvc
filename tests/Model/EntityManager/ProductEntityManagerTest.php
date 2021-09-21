<?php

declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\ProductEntityManager;

class ProductEntityManagerTest extends TestCase
{
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected ProductRepository $productRepository;
    protected CategoryRepository $categoryRepository;
    protected Database $database;
    protected ProductMapper $productMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();

        $this->productMapper = new ProductMapper();
        $categoryMapper = new CategoryMapper();

        $this->productRepository = new ProductRepository($this->database, $this->productMapper);
        $this->categoryRepository = new CategoryRepository($this->database, $categoryMapper);

        $this->categoryEntityManager = new CategoryEntityManager($this->database);
        $this->productEntityManager = new ProductEntityManager($this->database, $this->productRepository);

        $mappedCategory = $categoryMapper->map(['CategoryName' => 'Test']);
        $this->categoryEntityManager->insert($mappedCategory);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $category = $this->categoryRepository->getByName('Test');
        $this->categoryEntityManager->delete($category->id);

        $product = $this->productRepository->getByName('Test');
        if($product !== null){
            $this->productEntityManager->delete($product->id);
        }

        $this->database->disconnect();
    }

    public function testInsertProduct(): void
    {
        $categoryID = $this->categoryRepository->getByName('Test')->id;

        $mappedProduct = $this->productMapper->map(['ProductName' => 'Test', 'CategoryID' => $categoryID]);

        $this->productEntityManager->insert($mappedProduct);

        $actual = $this->productRepository->getByName('Test');

        self::assertSame('Test', $actual->productname);
        self::assertSame('', $actual->description);
    }

    public function testUpdateProduct(): void
    {
        $categoryID = $this->categoryRepository->getByName('Test')->id;
        $mappedProduct = $this->productMapper->map(['ProductName' => 'Test', 'CategoryID' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $product = $this->productRepository->getByName('Test');

        $mappedProduct = $this->productMapper->map(['ProductID' => $product->id, 'ProductName' => $product->productname, 'ProductDescription' => 'Nix']);
        $this->productEntityManager->update($mappedProduct);

        $actual = $this->productRepository->getByName('Test');
        self::assertSame('Nix', $actual->description);
    }

    public function testDeleteProduct(): void
    {
        $categoryID = $this->categoryRepository->getByName('Test')->id;
        $mappedProduct = $this->productMapper->map(['ProductName' => 'Test', 'CategoryID' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $id = $this->productRepository->getByName('Test')->id;
        $this->productEntityManager->delete($id);

        self::assertNull($this->productRepository->getByID($id));
    }
}