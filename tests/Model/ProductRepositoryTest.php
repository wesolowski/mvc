<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    public function testGetProduct(){
        $productRepository = new ProductRepository();
        $id = 10;
        self::assertSame((string)$id, $productRepository->getProduct($id)->id);
        self::assertSame('Shirt', $productRepository->getProduct($id)->productname);
        self::assertSame("black shirt, different sizes with print 'Here could be your advertising'", $productRepository->getProduct($id)->description);
    }
}