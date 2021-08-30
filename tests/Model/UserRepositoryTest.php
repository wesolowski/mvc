<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\Dto\UserDataTransferObject;
use App\Model\UserRepository;
use http\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testGetByNameWhenUsernameExists(): void{
        $userRepository = new UserRepository();
        $username = 'maxmustermann';

        $userTransferObject = $userRepository->getByUsername($username);

        //self::assertInstanceOf(UserDataTransferObject::class, $userTransferObject); Bei Null zum checken

        self::assertSame('1', $userTransferObject->id);
        self::assertSame('maxmustermann', $userTransferObject->username);
        self::assertSame('123456789', $userTransferObject->password);

    }
    public function testGetByNameWhenUsernameNotExists(): void{
        $userRepository = new UserRepository();
        $username = 'name';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User not found');

        $userRepository->getByUsername($username);
    }
}