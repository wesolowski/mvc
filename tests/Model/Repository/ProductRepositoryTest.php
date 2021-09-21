<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected CategoryProductEntityManager $categoryProductEntityManager;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->db = new Database(['database' => 'MVC_Test']);
        $db->connect();

        $categoryMapper = new CategoryMapper();
        $productMapper = new ProductMapper();

        $this->categoryEntityManager = new CategoryEntityManager($db);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'Test']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->productEntityManager = new ProductEntityManager($db);
        $mappedProduct = $productMapper->map(['ProductName' => 'Test', 'ProductDescription' => 'Desc']);
        $this->productEntityManager->insert($mappedProduct);

        $this->categoryRepository = new CategoryRepository($db, $categoryMapper);
        $this->productRepository = new ProductRepository($db, $productMapper);

        $categoryID = $this->categoryRepository->getByName('Test')->id;
        $productID = $this->productRepository->getByName('Test')->id;

        $this->categoryProductEntityManager = new CategoryProductEntityManager($db);
        $this->categoryProductEntityManager->insert($categoryID, $productID);

        $_GET['categoryID'] = $this->categoryRepository->getByName('Test')->id;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];

        $category = $this->categoryRepository->getByName('Test');
        $product = $this->productRepository->getByName('Test');

        $this->categoryProductEntityManager->delete($category->id, $product->id);

        $this->categoryEntityManager->delete($category->id);
        $this->productEntityManager->delete($product->id);

        $this->db->disconnect();
    }

    public function testGetProductByName(): void
    {
        $actual = $this->productRepository->getByName('Test');

        self::assertSame('Test', $actual->productname);
        self::assertSame("Desc", $actual->description);
    }

    public function testGetProductById(): void
    {
        $product = $this->productRepository->getByName('Test');
        $actual = $this->productRepository->getByID($product->id);

        self::assertSame('Test', $actual->productname);
        self::assertSame("Desc", $actual->description);
    }

    public function testGetList(): void
    {
        $productList = $this->productRepository->getList();
        $product = $this->productRepository->getByName('Test');

        self::assertCount(1, $productList);

        $actual = $productList[$product->id];
        self::assertSame('Test', $actual->productname);
        self::assertSame("Desc", $actual->description);
    }

    public function testGetExcludeList(): void
    {
        $productList = $this->productRepository->getListExcludeCategory();

        self::assertCount(0, $productList);
    }
}