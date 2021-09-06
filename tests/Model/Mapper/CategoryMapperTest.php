<?php
declare(strict_types=1);

namespace AppTest\Model\Mapper;

use App\Model\Mapper\CategoryMapper;
use PHPUnit\Framework\TestCase;

class CategoryMapperTest extends TestCase
{
    protected CategoryMapper $categoryMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryMapper = new CategoryMapper();
    }

    public function testMap(): void
    {
        $category = [
            'id' => '1',
            'categoryname' => 'Test'
        ];
        $mappedCategory = $this->categoryMapper->map($category);

        self::assertSame('1', $mappedCategory->id);
        self::assertSame('Test', $mappedCategory->categoryname);
    }
    public function testWhenCategoryIsEmpty(): void
    {
        $category = [];
        $mappedCategory = $this->categoryMapper->map($category);

        self::assertSame('', $mappedCategory->id);
        self::assertSame('', $mappedCategory->categoryname);
    }
}