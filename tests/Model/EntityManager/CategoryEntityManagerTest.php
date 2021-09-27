<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Mapper\CategoryMapper;
use App\Model\Repository\CategoryRepository;
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
    protected CategoryDataTransferObject $categoryDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();
        $this->categoryMapper = new CategoryMapper();
        $this->categoryRepository = new CategoryRepository($this->database, $this->categoryMapper);
        $this->categoryEntityManager = new CategoryEntityManager($this->database);

        $categoryDTO = $this->categoryMapper->map(['name' => 'Test']);
        $this->categoryEntityManager->insert($categoryDTO);
        $this->categoryDTO = $this->categoryRepository->getByName('Test');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $this->database->disconnect();
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
    }
}