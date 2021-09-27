<?php

declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Controller\Frontend\Product;
use App\Model\Database;
use App\Model\Dto\ProductDataTransferObject;
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
    protected ProductDataTransferObject $productDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();

        $this->productMapper = new ProductMapper();
        $categoryMapper = new CategoryMapper();

        $this->categoryRepository = new CategoryRepository($this->database, $categoryMapper);
        $this->productRepository = new ProductRepository($this->database, $this->productMapper);

        $this->categoryEntityManager = new CategoryEntityManager($this->database);
        $this->productEntityManager = new ProductEntityManager($this->database, $this->productRepository);

        $categoryDTO = $categoryMapper->map(['name' => 'ProductCategoryEMT']);
        $this->categoryEntityManager->insert($categoryDTO);

        $categoryDTOId = $this->categoryRepository->getByName('ProductCategoryEMT')->id;
        $productDTO = $this->productMapper->map(['name' => 'ProductEMT', 'price' => 1.20, 'description' => 'test', 'categoryId' => $categoryDTOId]);
        $this->productEntityManager->insert($productDTO);
        $this->productDTO = $this->productRepository->getByName('ProductEMT');
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

        $this->database->disconnect();
    }

    public function testInsertProduct(): void
    {
        self::assertSame('ProductEMT', $this->productDTO->name);
        self::assertSame(1.20, $this->productDTO->price);
        self::assertSame('test', $this->productDTO->description);
    }

    public function testUpdateProduct(): void
    {
        $productDTO = $this->productMapper->map(['id' => $this->productDTO->id, 'name' => $this->productDTO->name, 'price' => $this->productDTO->price, 'description' => 'Nix']);
        $this->productEntityManager->update($productDTO);

        $this->productDTO = $this->productRepository->getByName('ProductEMT');
        self::assertSame('ProductEMT', $this->productDTO->name);
        self::assertSame(1.20, $this->productDTO->price);
        self::assertSame('Nix', $this->productDTO->description);
    }

    public function testDeleteProduct(): void
    {
        $this->productEntityManager->delete($this->productDTO->id);

        self::assertNull($this->productRepository->getByID($this->productDTO->id));
    }
}