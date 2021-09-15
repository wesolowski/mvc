<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;
/*
use App\Model\Mapper\CategoryMapper;
use App\Model\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Database;

class CategoryEntityManagerTest extends TestCase
{
    protected CategoryEntityManager $categoryEntityManager;
    protected Database $database;
    protected CategoryRepository $categoryRepository;
    protected CategoryMapper $categoryMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->categoryRepository = new CategoryRepository($this->database);
        $this->categoryEntityManager = new CategoryEntityManager($this->database);
        $this->categoryMapper = new CategoryMapper();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testInsertCategory(): void
    {
        $mappedCategory = $this->categoryMapper->map(['CategoryName' => 'Test']);
        $this->categoryEntityManager->insert($mappedCategory);

        $category = $this->categoryRepository->getByName('Test');

        self::assertSame('Test', $category->categoryname);
    }

    public function testUpdateCategory(): void
    {
        $user = $this->categoryRepository->getByName('Test');
        $mappedCategory = $this->categoryMapper->map(['CategoryName' => 'Test2', 'CategoryID' => $user->id]);

        $this->categoryEntityManager->update($mappedCategory);

        $category = $this->categoryRepository->getByName('Test2');

        self::assertSame('Test2', $category->categoryname);
    }

    public function testDeleteCategory(): void
    {
        $category = $this->categoryRepository->getByName('Test2');

        $this->categoryEntityManager->delete($category->id);

        self::assertNull($this->categoryRepository->getByName('Test2'));
    }
}
*/