<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\ProductEntityManager;

class ProductEntityManagerTest extends TestCase
{
    protected ProductEntityManager $productEntityManager;
    protected function setUp(): void
    {
        parent::setUp();
        $this->productEntityManager = new ProductEntityManager();
    }

    public function testInsertProduct(): void{

    }

    public function testUpdateProduct(): void{

    }

    public function testDeleteProduct(): void{

    }
}