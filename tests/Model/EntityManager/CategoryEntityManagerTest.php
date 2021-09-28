<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Controller\Frontend\Product;
use App\Model\Dto\CategoryDataTransferObject;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use AppTest\Controller\Frontend\CategoryTest;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Database;

class CategoryEntityManagerTest extends TestCase
{
    protected CategoryEntityManager $categoryEntityManager;
    protected Database $database;
    protected CategoryRepository $categoryRepository;
    protected CategoryMapper $categoryMapper;
    protected ProductMapper $productMapper;
    protected CategoryDataTransferObject $categoryDTO;
    protected ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();
        $this->categoryMapper = new CategoryMapper();
        $this->productMapper = new ProductMapper();
        $this->categoryRepository = new CategoryRepository($this->database, $this->categoryMapper);
        $this->productRepository = new ProductRepository($this->database, $this->productMapper);
        $this->categoryEntityManager = new CategoryEntityManager($this->database);
        $productEntityManager = new ProductEntityManager($this->database, $this->productRepository);

        $categoryDTO = $this->categoryMapper->map(['name' => 'Test']);
        $this->categoryEntityManager->insert($categoryDTO);
        $this->categoryDTO = $this->categoryRepository->getByName('Test');

        $productDTO = $this->productMapper->map(['name' => 'TestPro', 'categoryId' => $this->categoryDTO->id]);
        $productEntityManager->insert($productDTO);
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
    }

    public function testInsertCategory(): void
    {
        self::assertSame('Test', $this->categoryDTO->name);
    }

    public function testUpdateCategory(): void
    {
        $categoryDTO = $this->categoryMapper->map(['name' => 'Test', 'id' => $this->categoryDTO->id]);
        $this->categoryEntityManager->update($categoryDTO);

        $this->categoryDTO = $this->categoryRepository->getByName('Test');
        self::assertSame('Test', $this->categoryDTO->name);
    }

    public function testDeleteCategory(): void
    {
        $this->categoryEntityManager->delete($this->categoryDTO->id);

        self::assertNull($this->categoryRepository->getByName('Test'));

        $productDTO = $this->productRepository->getByName('TestPro');

        self::assertSame('TestPro', $productDTO->name);
    }
}