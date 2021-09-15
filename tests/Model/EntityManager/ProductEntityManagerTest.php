<?php

declare(strict_types=1);

namespace AppTest\Model\EntityManager;

/*
use App\Model\Database;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\ProductEntityManager;

class ProductEntityManagerTest extends TestCase
{
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
        $this->productRepository = new ProductRepository(1, $this->database);
        $this->categoryRepository = new CategoryRepository($this->database);
        $this->productEntityManager = new ProductEntityManager($this->database, $this->productRepository);
        $this->productMapper = new ProductMapper();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }
    public function testInsertProduct(): void
    {
        $categoryID = $this->categoryRepository->getByName('Media')->id;

        $mappedProduct = $this->productMapper->map(['ProductName' => 'TestProduct', 'CategoryID' => $categoryID]);

        $this->productEntityManager->insert($mappedProduct);

        $actual = $this->productRepository->getByName('TestProduct');

        self::assertSame('TestProduct', $actual->productname);
        self::assertSame('', $actual->description);
    }
    public function testUpdateProduct(): void
    {
        $product = $this->productRepository->getByName('TestProduct');

        $mappedProduct = $this->productMapper->map(['ProductID' => $product->id, 'ProductName' => $product->productname, 'ProductDescription' => 'Nix']);

        $this->productEntityManager->update($mappedProduct);

        $actual = $this->productRepository->getByName('TestProduct');
        self::assertSame('Nix', $actual->description);
    }
    public function testDeleteProduct(): void
    {
        $id = $this->productRepository->getByName('TestProduct')->id;

        $this->productEntityManager->delete($id);

        self::assertNull($this->productRepository->getByID($id));
    }
}
*/