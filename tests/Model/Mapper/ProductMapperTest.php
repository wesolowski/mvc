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
            'ProductID' => 1,
            'ProductName' => 'john doe',
            'ProductDescription' => 'hahaha'
        ];
        $productDataTransfer = $productMapper->map(
            $expected
        );

        self::assertSame($expected['ProductID'],$productDataTransfer->id);
        self::assertSame($expected['ProductName'],$productDataTransfer->productname);
        self::assertSame($expected['ProductDescription'],$productDataTransfer->description);
    }


    public function testWhenProductIsEmpty() {
        $productMapper = new ProductMapper();

        $productDataTransfer = $productMapper->map([]);

        self::assertSame(0, $productDataTransfer->id);
        self::assertSame('', $productDataTransfer->description);
        self::assertSame('', $productDataTransfer->productname);
    }
}