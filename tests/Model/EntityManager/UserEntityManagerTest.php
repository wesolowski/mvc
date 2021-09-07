<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\UserEntityManager;

class UserEntityManagerTest extends TestCase
{
    protected UserEntityManager $userEntityManager;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userEntityManager = new UserEntityManager();
    }

    public function testInsertUser(): void{

    }

    public function testUpdateUser(): void{

    }

    public function testDeleteUser(): void{

    }
}