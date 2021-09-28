<?php
declare(strict_types=1);

namespace AppTest\Model\Mapper;

use App\Model\Mapper\ProductMapper;
use PHPUnit\Framework\TestCase;

class ProductMapperTest extends TestCase
{
    public function test(): void
    {
        $productMapper = new ProductMapper();

        $expected = [
            'id' => '1',
            'name' => 'john doe',
            'price' => '9.99',
            'description' => 'hahaha',
            'categoryId' => '1'
        ];
        $productDataTransfer = $productMapper->map($expected);

        self::assertSame(1, $productDataTransfer->id);
        self::assertSame($expected['name'], $productDataTransfer->name);
        self::assertSame(9.99, $productDataTransfer->price);
        self::assertSame($expected['description'], $productDataTransfer->description);
        self::assertSame(1, $productDataTransfer->categoryId);
    }


    public function testWhenProductIsEmpty(): void
    {
        $productMapper = new ProductMapper();

        $productDataTransfer = $productMapper->map([]);

        self::assertSame(0, $productDataTransfer->id);
        self::assertSame(0.00, $productDataTransfer->price);
        self::assertSame(0, $productDataTransfer->categoryId);
        self::assertNull($productDataTransfer->description);
        self::assertSame('', $productDataTransfer->name);
    }
}
