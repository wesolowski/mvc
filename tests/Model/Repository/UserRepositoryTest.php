<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Model\Database;
use App\Model\Mapper\UserMapper;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $userRepository;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->db = new Database(['database' => 'MVC_Test']);
        $db->connect();
        $this->userRepository = new UserRepository($db, new UserMapper());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testGetByNameWhenUsernameExists(): void
    {
        $userTransferObject = $this->userRepository->getByUsername('maxmustermann');

        self::assertSame(1, $userTransferObject->id);
        self::assertSame('maxmustermann', $userTransferObject->username);
        self::assertSame('123', $userTransferObject->password);

        $userTransferObject = $this->userRepository->getByUsername('philipphermes');

        self::assertSame(2, $userTransferObject->id);
        self::assertSame('philipphermes', $userTransferObject->username);
        self::assertSame('456', $userTransferObject->password);
    }
}