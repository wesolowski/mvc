<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function testGetByNameWhenUsernameExists(): void
    {
        $userTransferObject = $this->userRepository->getByUsername('maxmustermann');

        //self::assertInstanceOf(UserDataTransferObject::class, $userTransferObject); Bei Null zum checken

        self::assertSame('1', $userTransferObject->id);
        self::assertSame('maxmustermann', $userTransferObject->username);
        self::assertSame('123', $userTransferObject->password);

        $userTransferObject = $this->userRepository->getByUsername('harunguer');

        self::assertSame('2', $userTransferObject->id);
        self::assertSame('harunguer', $userTransferObject->username);
        self::assertSame('456', $userTransferObject->password);

        $userTransferObject = $this->userRepository->getByUsername('philipphermes');

        self::assertSame('3', $userTransferObject->id);
        self::assertSame('philipphermes', $userTransferObject->username);
        self::assertSame('789', $userTransferObject->password);
    }

    public function testGetByNameWhenUsernameNotExists(): void
    {
        $username = 'name';
        self::assertNull($this->userRepository->getByUsername($username));
    }
}