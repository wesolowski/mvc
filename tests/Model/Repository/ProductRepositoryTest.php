<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Core\Redirect;
use App\Model\Database;
use App\Model\Repository\ProductRepository;
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
        $this->productRepository = new ProductRepository(1, $db);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testGetProductByIdWhenExists(): void
    {
        $id = 5;

        self::assertSame(5, $this->productRepository->getByID($id)->id);
        self::assertSame('Titanfall 2', $this->productRepository->getByID($id)->productname);
        self::assertSame("Price: 29,99 €", $this->productRepository->getByID($id)->description);
    }

    public function testGetProductByNameWhenExists(): void
    {
        $name = 'Titanfall 2';
        $actual = $this->productRepository->getByName($name);
        self::assertSame(5, $actual->id);
        self::assertSame('Titanfall 2', $actual->productname);
        self::assertSame("Price: 29,99 €", $actual->description);
    }

    public function testGetList(): void
    {
        $productList = $this->productRepository->getList();

        self::assertCount(2, $productList);

        $product = $productList[5];
        self::assertSame("Titanfall 2", $product->productname);
        self::assertSame("Price: 29,99 €", $product->description);
        self::assertSame("5", $product->id);
    }
}