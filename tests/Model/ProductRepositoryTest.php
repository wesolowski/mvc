<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\ProductRepository;
use http\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    public function testGetProductByIdWhenExists(): void{
        $productRepository = new ProductRepository();
        $id = 10;
        self::assertSame((string)$id, $productRepository->getProduct($id)->id);
        self::assertSame('Shirt', $productRepository->getProduct($id)->productname);
        self::assertSame("black shirt, different sizes with print 'Here could be your advertising'", $productRepository->getProduct($id)->description);
    }
    public function testGetProductByIdWhenNotExists(): void{
        $productRepository = new ProductRepository();
        $id = 1;

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Product not found');

        $productRepository->getProduct($id);
    }
}