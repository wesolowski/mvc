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
    protected Database $db;
    protected CategoryEntityManager $categoryEntityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = new Database(['database' => 'MVC_Test']);
        $this->db->connect();
        $this->categoryRepository = new CategoryRepository($this->db, new CategoryMapper());
        $categoryMapper = new CategoryMapper();
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'Test']);
        $this->categoryEntityManager = new CategoryEntityManager($this->db);
        $this->categoryEntityManager->insert($mappedCategory);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $category = $this->categoryRepository->getByName('Test');
        $this->categoryEntityManager->delete($category->id);
        $this->db->disconnect();
    }

    public function testGetList(): void
    {
        $categoryList = $this->categoryRepository->getList();
        $category = $this->categoryRepository->getByName('Test');

        self::assertCount(5, $categoryList);
        self::assertSame('Test', $categoryList[$category->id]->categoryname);
    }

    public function testGetById(): void
    {
        $category = $this->categoryRepository->getByName('Test');
        $categoryByID = $this->categoryRepository->getById($category->id);
        self::assertSame('Test', $categoryByID->categoryname);
    }
}