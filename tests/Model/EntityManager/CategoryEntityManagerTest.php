<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

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
        $this->categoryMapper = new CategoryMapper();
        $this->categoryRepository = new CategoryRepository($this->database, $this->categoryMapper);
        $this->categoryEntityManager = new CategoryEntityManager($this->database);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $categoryTest = $this->categoryRepository->getByName('Test');
        $categoryTes = $this->categoryRepository->getByName('Tes');

        if($categoryTest !== null) {
            $this->categoryEntityManager->delete($categoryTest->id);
        }
        if($categoryTes !== null) {
            $this->categoryEntityManager->delete($categoryTes->id);
        }

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
        $mappedCategory = $this->categoryMapper->map(['CategoryName' => 'Tes']);
        $this->categoryEntityManager->insert($mappedCategory);

        $category = $this->categoryRepository->getByName('Tes');
        $mappedCategory = $this->categoryMapper->map(['CategoryName' => 'Test', 'CategoryID' => $category->id]);
        $this->categoryEntityManager->update($mappedCategory);

        $category = $this->categoryRepository->getByName('Test');
        self::assertSame('Test', $category->categoryname);
    }

    public function testDeleteCategory(): void
    {
        $mappedCategory = $this->categoryMapper->map(['CategoryName' => 'Test']);
        $this->categoryEntityManager->insert($mappedCategory);

        $category = $this->categoryRepository->getByName('Test');

        $this->categoryEntityManager->delete($category->id);

        self::assertNull($this->categoryRepository->getByName('Test'));
    }
}