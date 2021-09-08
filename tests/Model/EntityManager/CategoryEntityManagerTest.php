<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Model\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Database;

class CategoryEntityManagerTest extends TestCase
{
    protected CategoryEntityManager $categoryEntityManager;
    protected Database $database;
    protected CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->categoryRepository = new CategoryRepository($this->database);
        $this->categoryEntityManager = new CategoryEntityManager($this->database, $this->categoryRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testInsertCategory(): void
    {
        $this->categoryEntityManager->insert(['categoryname' => 'Test']);

        $category = $this->categoryRepository->getByName('Test');

        self::assertSame('Test', $category->categoryname);
    }

    public function testInsertNoDataGiven(): void
    {
        $actual = $this->categoryEntityManager->insert();

        self::assertSame('Category musst be given', $actual);
    }

    public function testUpdateCategory(): void
    {
        $user = $this->categoryRepository->getByName('Test');

        $this->categoryEntityManager->update(['categoryname' => 'Test2', 'id' => $user->id]);

        $category = $this->categoryRepository->getByName('Test2');

        self::assertSame('Test2', $category->categoryname);
    }

    public function testUpdateCategoryNoDataGiven(): void{
        $actual = $this->categoryEntityManager->update();

        self::assertSame('Category and ID musst be given', $actual);
    }

    public function testDeleteCategory(): void
    {
        $category = $this->categoryRepository->getByName('Test2');

        $this->categoryEntityManager->delete($category->id);

        self::assertNull($this->categoryRepository->getByName('Test2'));
    }

    public function testDeleteCategoryIdNotGiven(): void
    {
        $actual = $this->categoryEntityManager->delete('');

        self::assertSame('Id musst be given', $actual);
    }
}