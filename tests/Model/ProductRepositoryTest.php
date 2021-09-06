<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Core\Redirect;
use App\Model\Database;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    protected ProductRepository $productRepository;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->db = new Database();
        $db->connect();
        $this->productRepository = new ProductRepository('1$Media', new Redirect(), $db);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testGetProductByIdWhenExists(): void
    {
        $id = '5';

        self::assertSame((string)$id, $this->productRepository->getProduct($id)->id);
        self::assertSame('Titanfall 2', $this->productRepository->getProduct($id)->productname);
        self::assertSame("Price: 29,99 €", $this->productRepository->getProduct($id)->description);
    }
    public function testGetProductByIdWhenNotExists(): void
    {
        $id = '3';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Product not found');

        $this->productRepository->getProduct($id);
    }

    public function testGetList(): void
    {
        $productList = $this->productRepository->getList();

        self::assertCount(2, $productList);

        $product = $productList['5'];
        self::assertSame("Titanfall 2", $product->productname);
        self::assertSame("Price: 29,99 €", $product->description);
        self::assertSame("5", $product->id);
    }
}