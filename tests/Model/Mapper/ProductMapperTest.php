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
            'id' => 1,
            'name' => 'john doe',
            'price' => 9.99,
            'description' => 'hahaha'
        ];
        $productDataTransfer = $productMapper->map($expected);

        self::assertSame($expected['id'], $productDataTransfer->id);
        self::assertSame($expected['name'], $productDataTransfer->name);
        self::assertSame($expected['price'], $productDataTransfer->price);
        self::assertSame($expected['description'], $productDataTransfer->description);
    }


    public function testWhenProductIsEmpty(): void
    {
        $productMapper = new ProductMapper();

        $productDataTransfer = $productMapper->map([]);

        self::assertSame(0, $productDataTransfer->id);
        self::assertSame('', $productDataTransfer->description);
        self::assertSame('', $productDataTransfer->name);
    }
}
