<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Model\Database;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    protected ProductRepository $productRepository;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->db = new Database(['database' => 'MVC_Test']);
        $db->connect();
        $this->productRepository = new ProductRepository($db, new ProductMapper());
        $_GET['categoryID'] = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
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
        self::assertSame(5, $product->id);
    }

    public function testGetExcludeList(): void
    {
        $productList = $this->productRepository->getListExcludeCategory();

        self::assertCount(4, $productList);
    }
}