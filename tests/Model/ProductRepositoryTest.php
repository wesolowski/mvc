<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Core\Redirect;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    protected ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepository = new ProductRepository('Clothing', new Redirect());
    }

    public function testGetProductByIdWhenExists(): void
    {
        $id = '10';
        self::assertSame((string)$id, $this->productRepository->getProduct($id)->id);
        self::assertSame('Shirt', $this->productRepository->getProduct($id)->productname);
        self::assertSame("black shirt, different sizes with print 'Here could be your advertising'", $this->productRepository->getProduct($id)->description);
    }

    public function testGetProductByIdWhenNotExists(): void
    {
        $id = '1';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Product not found');

        $this->productRepository->getProduct($id);
    }

    public function testGetList(): void
    {
        $productList = $this->productRepository->getList();

        self::assertCount(1, $productList);

        $product = $productList[10];
        self::assertSame("Shirt", $product->productname);
        self::assertSame("black shirt, different sizes with print 'Here could be your advertising'", $product->description);
        self::assertSame("10", $product->id);
    }
}