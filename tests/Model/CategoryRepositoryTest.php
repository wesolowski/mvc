<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\CategoryRepository;
use PHPUnit\Framework\TestCase;

class CategoryRepositoryTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepository = new CategoryRepository();
    }

    public function testGetList(): void
    {
        $categoryList = $this->categoryRepository->getList();
        self::assertSame('1', $categoryList['1']->id);
        self::assertSame('Media', $categoryList['1']->categoryname);

        self::assertSame('2', $categoryList['2']->id);
        self::assertSame('Food', $categoryList['2']->categoryname);

        self::assertSame('3', $categoryList['3']->id);
        self::assertSame('Clothing', $categoryList['3']->categoryname);
    }

    public function testGetById(): void
    {
        $category = $this->categoryRepository->getById('1');
        self::assertSame('1', $category->id);
        self::assertSame('Media', $category->categoryname);
    }

    public function testHasCategory(): void
    {
        self::assertTrue($this->categoryRepository->hasCategory('1'));
    }

    public function testGetCategoryByIdWhenNotExists(): void
    {
        $id = '5';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Category not found');

        $this->categoryRepository->getById($id);
    }
}