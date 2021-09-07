<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\CategoryEntityManager;

class CategoryEntityManagerTest extends TestCase
{
    protected CategoryEntityManager $categoryEntityManager;
    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryEntityManager = new CategoryEntityManager();
    }

    public function testInsertCategory(): void{

    }

    public function testUpdateCategory(): void{

    }

    public function testDeleteCategory(): void{

    }
}