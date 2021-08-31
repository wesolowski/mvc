<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    public function testGetProductByIdWhenExists(): void
    {
        $productRepository = new ProductRepository();
        $id = 10;
        self::assertSame((string)$id, $productRepository->getProduct($id)->id);
        self::assertSame('Shirt', $productRepository->getProduct($id)->productname);
        self::assertSame("black shirt, different sizes with print 'Here could be your advertising'", $productRepository->getProduct($id)->description);
    }

    public function testGetProductByIdWhenNotExists(): void
    {
        $productRepository = new ProductRepository();
        $id = 1;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Product not found');

        $productRepository->getProduct($id);
    }

    public function testGetList(): void
    {
        $productRepository = new ProductRepository();

        $productList = $productRepository->getList();

        self::assertCount(4, $productList);

        $product = $productList[10];
        self::assertSame("Shirt", $product->productname);
        self::assertSame("black shirt, different sizes with print 'Here could be your advertising'", $product->description);
        self::assertSame("10", $product->id);

        $product = $productList[2];
        self::assertSame("Game", $product->productname);
        self::assertSame("a lovely game about eat, sleep, code, repeat.", $product->description);
        self::assertSame("2", $product->id);

        $product = $productList[3];
        self::assertSame("Cake", $product->productname);
        self::assertSame("The cake is a lie.", $product->description);
        self::assertSame("3", $product->id);

        $product = $productList[4];
        self::assertSame("Coffee", $product->productname);
        self::assertSame("Coffee is love, coffee is life.", $product->description);
        self::assertSame("4", $product->id);
    }
}