<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testGetByNameWhenUsernameExists(): void{
        $userRepository = new UserRepository();
        $username = 'maxmustermann';
        self::assertTrue($userRepository->hasUser($username));

        self::assertSame('1', $userRepository->getByUsername($username)->id);
        self::assertSame('maxmustermann', $userRepository->getByUsername($username)->username);
        self::assertSame('123456789', $userRepository->getByUsername($username)->password);

    }
    public function testGetByNameWhenUsernameNotExists(): void{
        $userRepository = new UserRepository();
        $username = 'name';
        self::assertFalse($userRepository->hasUser($username));
        self::assertNull($userRepository->getByUsername($username));
    }
}