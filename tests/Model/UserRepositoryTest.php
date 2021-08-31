<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\Dto\UserDataTransferObject;
use App\Model\UserRepository;
use http\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testGetByNameWhenUsernameExists(): void
    {
        $userRepository = new UserRepository();

        $userTransferObject = $userRepository->getByUsername('maxmustermann');

        //self::assertInstanceOf(UserDataTransferObject::class, $userTransferObject); Bei Null zum checken

        self::assertSame('1', $userTransferObject->id);
        self::assertSame('maxmustermann', $userTransferObject->username);
        self::assertSame('123', $userTransferObject->password);

        $userTransferObject = $userRepository->getByUsername('harunguer');

        self::assertSame('2', $userTransferObject->id);
        self::assertSame('harunguer', $userTransferObject->username);
        self::assertSame('456', $userTransferObject->password);

        $userTransferObject = $userRepository->getByUsername('philipphermes');

        self::assertSame('3', $userTransferObject->id);
        self::assertSame('philipphermes', $userTransferObject->username);
        self::assertSame('789', $userTransferObject->password);
    }

    public function testGetByNameWhenUsernameNotExists(): void
    {
        $userRepository = new UserRepository();
        $username = 'name';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User not found');

        $userRepository->getByUsername($username);
    }
}