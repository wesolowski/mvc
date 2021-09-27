<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use phpDocumentor\Reflection\DocBlock\Tags\Example;
use PHPUnit\Framework\TestCase;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use function PHPUnit\Framework\assertSame;

class CategoryProductEntityManagerTest extends TestCase
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
        $this->categoryProductEntityManager = new CategoryProductEntityManager($db);

        $mappedCategory = $categoryMapper->map(['name' => 'FirstCat']);
        $this->categoryEntityManager->insert($mappedCategory);

        $mappedCategory = $categoryMapper->map(['name' => 'SecondCat']);
        $this->categoryEntityManager->insert($mappedCategory);

        $categoryID = $this->categoryRepository->getByName('FirstCat')->id;
        $mappedProduct = $productMapper->map(['name' => 'CPEMProduct', 'description' => 'Desc', 'categoryId' => $categoryID]);
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
        $this->database->disconnect();
    }

    public function testInsert(): void
    {
        $category = $this->categoryRepository->getByName('SecondCat');
        $_GET['categoryId'] = $category->id;
        $product = $this->productRepository->getByName('CPEMProduct');
        $this->categoryProductEntityManager->insert($category->id, $product->id);

        $productId = $this->productRepository->getByName('CPEMProduct')->id;
        $productList = $this->productRepository->getList();

        self::assertSame('CPEMProduct', $productList[$productId]->name);
    }

    public function testDelete(): void
    {
        $category = $this->categoryRepository->getByName('SecondCat');
        $_GET['categoryId'] = $category->id;
        $product = $this->productRepository->getByName('CPEMProduct');
        $this->categoryProductEntityManager->insert($category->id, $product->id);

        $this->categoryProductEntityManager->delete($category->id, $product->id);

        $productId = $this->productRepository->getByName('CPEMProduct')->id;
        $productList = $this->productRepository->getListExcludeCategory();

        self::assertSame('CPEMProduct', $productList[$productId]->name);
    }

}