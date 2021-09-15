<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;
/*
use App\Model\Repository\CategoryRepository;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class CategoryRepositoryTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = new Database();
        $this->db->connect();
        $this->categoryRepository = new CategoryRepository($this->db);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testGetList(): void
    {
        $categoryList = $this->categoryRepository->getList();
        self::assertSame(1, $categoryList[1]->id);
        self::assertSame('Media', $categoryList[1]->categoryname);

        self::assertSame(2, $categoryList[2]->id);
        self::assertSame('Food', $categoryList[2]->categoryname);

        self::assertSame(3, $categoryList[3]->id);
        self::assertSame('Clothing', $categoryList[3]->categoryname);
    }

    public function testGetById(): void
    {
        $category = $this->categoryRepository->getById(1);
        self::assertSame(1, $category->id);
        self::assertSame('Media', $category->categoryname);
    }
}
*/