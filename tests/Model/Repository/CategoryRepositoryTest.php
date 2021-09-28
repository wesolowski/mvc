<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class CategoryRepositoryTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected Database $database;
    protected CategoryEntityManager $categoryEntityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();

        $categoryMapper = new CategoryMapper();
        $this->categoryRepository = new CategoryRepository($this->database, $categoryMapper);

        $this->categoryEntityManager = new CategoryEntityManager($this->database);

        $mappedCategory = $categoryMapper->map(['name' => 'CategoryRepoTest']);
        $this->categoryEntityManager->insert($mappedCategory);

        $mappedCategory = $categoryMapper->map(['name' => 'CategoryRepoTest2']);
        $this->categoryEntityManager->insert($mappedCategory);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function testGetList(): void
    {
        $categoryList = $this->categoryRepository->getList();
        $category = $this->categoryRepository->getByName('CategoryRepoTest');

        self::assertCount(2, $categoryList);
        self::assertSame('CategoryRepoTest', $categoryList[$category->id]->name);
        self::assertSame('CategoryRepoTest2', $categoryList[$category->id+1]->name);
    }

    public function testGetById(): void
    {
        $category = $this->categoryRepository->getByName('CategoryRepoTest');
        $categoryByID = $this->categoryRepository->getById($category->id);
        self::assertSame('CategoryRepoTest', $categoryByID->name);
    }
}