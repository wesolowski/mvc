<?php
declare(strict_types=1);

namespace AppTest\Model\Mapper;

use App\Model\Mapper\ProductMapper;
use PHPUnit\Framework\TestCase;

class ProductMapperTest extends TestCase
{
    public function test() {
        $productMapper = new ProductMapper();

        $expected = [
            'id' => '1',
            'productname' => 'john doe',
            'description' => 'hahaha'
        ];
        $productDataTransfer = $productMapper->map(
            $expected
        );

        self::assertSame($expected['id'],$productDataTransfer->id);
        self::assertSame($expected['productname'],$productDataTransfer->productname);
        self::assertSame($expected['description'],$productDataTransfer->description);
    }


    public function testWhenProductIsEmpty() {
        $productMapper = new ProductMapper();

        $productDataTransfer = $productMapper->map([]);

        self::assertSame('',$productDataTransfer->id);
        self::assertSame('',$productDataTransfer->description);
        self::assertSame('',$productDataTransfer->productname);
    }
}