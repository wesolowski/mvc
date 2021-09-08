<?php

declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Core\Redirect;
use App\Model\Database;
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

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->productRepository = new ProductRepository('1$Media', $this->database);
        $this->categoryRepository = new CategoryRepository($this->database);
        $this->productEntityManager = new ProductEntityManager($this->database, $this->productRepository, $this->categoryRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testInsertProduct(): void
    {
        $categoryID = $this->categoryRepository->getByName('Media')->id;
        $this->productEntityManager->insert(['productname' => 'TestProduct', 'categoryID' => $categoryID]);
        $actual = $this->productRepository->getByName('TestProduct');
        self::assertSame('TestProduct', $actual->productname);
        self::assertSame('', $actual->description);
    }

    public function testInsertProductExists(): void
    {
        $categoryID = $this->categoryRepository->getByName('Media')->id;
        $actual = $this->productEntityManager->insert(['productname' => 'TestProduct', 'categoryID' => $categoryID]);
        self::assertSame('Product already exists or/and Category does not exist', $actual);
    }

    public function testInsertProductNoDataGiven(): void
    {
        $actual = $this->productEntityManager->insert();
        self::assertSame('Productname and Category ID musst be given', $actual);
    }

    public function testUpdateProduct(): void
    {
        $product = $this->productRepository->getByName('TestProduct');
        $this->productEntityManager->update(['id' => $product->id, 'productname' => $product->productname, 'description' => 'Jetzt ist nicht mehr null']);
        $actual = $this->productRepository->getByName('TestProduct');
        self::assertSame('Jetzt ist nicht mehr null', $actual->description);
    }

    public function testUpdateProductDoesntExist(): void
    {
        $product = $this->productRepository->getByName('TestProduct');
        $actual = $this->productEntityManager->update(['id' => '100', 'productname' => $product->productname, 'description' => 'Jetzt ist nicht mehr null']);
        self::assertSame('Product doesnt exist', $actual);
    }

    public function testUpdateProductNoDataGiven(): void
    {
        $actual = $this->productEntityManager->update();
        self::assertSame('Productname and ID musst be given', $actual);
    }

    public function testDeleteProduct(): void
    {
        $id = $this->productRepository->getByName('TestProduct')->id;
        $this->productEntityManager->delete($id);
        self::assertFalse($this->productRepository->hasProduct(['id' => $id]));
    }

    public function testDeleteIdNotGiven(): void
    {
        $actual = $this->productEntityManager->delete('');
        self::assertSame('Id musst be given', $actual);
    }
}