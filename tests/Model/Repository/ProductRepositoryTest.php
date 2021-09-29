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
        $db = $this->database = new Database(['database' => 'mvc_test']);
        $db->connect();

        $categoryMapper = new CategoryMapper();
        $productMapper = new ProductMapper();
        $this->categoryRepository = new CategoryRepository($db, $categoryMapper);
        $this->productRepository = new ProductRepository($db, $productMapper);

        $this->categoryEntityManager = new CategoryEntityManager($db);
        $this->productEntityManager = new ProductEntityManager($db, $this->productRepository);

        $mappedCategory = $categoryMapper->map(['name' => 'CategoryProductRepoTest2']);
        $this->categoryEntityManager->insert($mappedCategory);
        $categoryID = $this->categoryRepository->getByName('CategoryProductRepoTest2')->id;
        $mappedProduct = $productMapper->map(['name' => 'ProductRepoTest3', 'categoryId' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);
        $mappedProduct = $productMapper->map(['name' => 'ProductRepoTest4', 'categoryId' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);


        $mappedCategory = $categoryMapper->map(['name' => 'CategoryProductRepoTest']);
        $this->categoryEntityManager->insert($mappedCategory);

        $categoryID = $this->categoryRepository->getByName('CategoryProductRepoTest')->id;
        $_GET['categoryId'] = $categoryID;

        $mappedProduct = $productMapper->map(['name' => 'ProductRepoTest', 'categoryId' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);
        $mappedProduct = $productMapper->map(['name' => 'ProductRepoTest2', 'categoryId' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE categoryProduct');
        $connection->query('TRUNCATE product');
        $connection->query('TRUNCATE category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $_GET = [];
    }

    public function testGetProductByName(): void
    {
        $actual = $this->productRepository->getByName('ProductRepoTest');

        self::assertSame('ProductRepoTest', $actual->name);
    }

    public function testGetProductById(): void
    {
        $product = $this->productRepository->getByName('ProductRepoTest');
        $actual = $this->productRepository->getByID($product->id);

        self::assertSame('ProductRepoTest', $actual->name);
    }

    public function testGetList(): void
    {
        $productList = $this->productRepository->getList();
        $product = $this->productRepository->getByName('ProductRepoTest');

        self::assertCount(2, $productList);

        self::assertSame('ProductRepoTest', $productList[$product->id]->name);
        self::assertSame('ProductRepoTest2', $productList[$product->id+1]->name);
    }

    public function testGetListNoIdGiven(): void
    {
        unset($_GET);
        $productList = $this->productRepository->getList();

        self::assertEmpty($productList);
    }

    public function testGetExcludeList(): void
    {
        $productList = $this->productRepository->getListExcludeCategory();
        $product = $this->productRepository->getByName('ProductRepoTest3');

        self::assertCount(2, $productList);

        self::assertSame('ProductRepoTest3', $productList[$product->id]->name);
        self::assertSame('ProductRepoTest4', $productList[$product->id+1]->name);
    }

    public function testGetExcludeListNoIdGiven(): void
    {
        unset($_GET);
        $productListExclude = $this->productRepository->getListExcludeCategory();
        $product = $this->productRepository->getByName('ProductRepoTest3');

        self::assertCount(4, $productListExclude);

        self::assertSame('ProductRepoTest3', $productListExclude[$product->id]->name);
        self::assertSame('ProductRepoTest4', $productListExclude[$product->id+1]->name);
        self::assertSame('ProductRepoTest', $productListExclude[$product->id+2]->name);
        self::assertSame('ProductRepoTest2', $productListExclude[$product->id+3]->name);

    }
}