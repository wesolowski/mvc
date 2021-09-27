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
    protected Database $database;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->database = new Database(['database' => 'MVC_Test']);
        $db->connect();

        $categoryMapper = new CategoryMapper();
        $productMapper = new ProductMapper();
        $this->categoryRepository = new CategoryRepository($db, $categoryMapper);
        $this->productRepository = new ProductRepository($db, $productMapper);

        $this->categoryEntityManager = new CategoryEntityManager($db);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'CategoryProductRepoTest']);
        $this->categoryEntityManager->insert($mappedCategory);

        $categoryID = $this->categoryRepository->getByName('CategoryProductRepoTest')->id;
        $_GET['categoryID'] = $categoryID;

        $this->productEntityManager = new ProductEntityManager($db, $this->productRepository);
        $mappedProduct = $productMapper->map(['ProductName' => 'ProductRepoTest', 'ProductDescription' => 'Desc', 'CategoryID' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE CategoryProduct');
        $connection->query('TRUNCATE Product');
        $connection->query('TRUNCATE Category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $_GET = [];
        $this->database->disconnect();
    }

    public function testGetProductByName(): void
    {
        $actual = $this->productRepository->getByName('ProductRepoTest');

        self::assertSame('ProductRepoTest', $actual->name);
        self::assertSame("Desc", $actual->description);
    }

    public function testGetProductById(): void
    {
        $product = $this->productRepository->getByName('ProductRepoTest');
        $actual = $this->productRepository->getByID($product->id);

        self::assertSame('ProductRepoTest', $actual->name);
        self::assertSame("Desc", $actual->description);
    }

    public function testGetList(): void
    {
        $productList = $this->productRepository->getList();
        $product = $this->productRepository->getByName('ProductRepoTest');

        self::assertCount(1, $productList);

        $actual = $productList[$product->id];
        self::assertSame('ProductRepoTest', $actual->productname);
        self::assertSame("Desc", $actual->description);
    }

    public function testGetExcludeList(): void
    {
        $productList = $this->productRepository->getListExcludeCategory();

        self::assertCount(0, $productList);
    }
}