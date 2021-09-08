<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Model\Database;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $userRepository;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->db = new Database();
        $db->connect();
        $this->userRepository = new UserRepository($db);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testGetByNameWhenUsernameExists(): void
    {
        $userTransferObject = $this->userRepository->getByUsername('maxmustermann');

        self::assertSame('1', $userTransferObject->id);
        self::assertSame('maxmustermann', $userTransferObject->username);
        self::assertSame('123', $userTransferObject->password);

        $userTransferObject = $this->userRepository->getByUsername('philipphermes');

        self::assertSame('2', $userTransferObject->id);
        self::assertSame('philipphermes', $userTransferObject->username);
        self::assertSame('456', $userTransferObject->password);
    }

    public function testGetByNameWhenUsernameNotExists(): void
    {
        $username = 'name';
        self::assertNull($this->userRepository->getByUsername($username));
    }

    public function testGetByIDWhenUsernameNotExists(): void
    {
        $id = '10';
        self::assertNull($this->userRepository->getByID($id));
    }
}