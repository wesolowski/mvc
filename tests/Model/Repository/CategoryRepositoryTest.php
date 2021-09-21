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

        $categoryMapper = new CategoryMapper();
        $this->categoryRepository = new CategoryRepository($this->db, $categoryMapper);

        $this->categoryEntityManager = new CategoryEntityManager($this->db);

        $mappedCategory = $categoryMapper->map(['CategoryName' => 'CategoryRepoTest']);
        $this->categoryEntityManager->insert($mappedCategory);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $category = $this->categoryRepository->getByName('CategoryRepoTest');

        $this->categoryEntityManager->delete($category->id);

        $this->db->disconnect();
    }

    public function testGetList(): void
    {
        $categoryList = $this->categoryRepository->getList();
        $category = $this->categoryRepository->getByName('CategoryRepoTest');

        self::assertCount(1, $categoryList);
        self::assertSame('CategoryRepoTest', $categoryList[$category->id]->categoryname);
    }

    public function testGetById(): void
    {
        $category = $this->categoryRepository->getByName('CategoryRepoTest');
        $categoryByID = $this->categoryRepository->getById($category->id);
        self::assertSame('CategoryRepoTest', $categoryByID->categoryname);
    }
}