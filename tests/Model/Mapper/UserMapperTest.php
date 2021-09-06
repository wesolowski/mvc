<?php
declare(strict_types=1);

namespace AppTest\Model\Mapper;

use App\Model\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    public function testMapper(): void{
        $userMapper = new UserMapper();
        $expected = [
            'UserID' => '1',
            'Username' => 'maxmusterman',
            'Password' => '123456789'
        ];
        $userDto = $userMapper->map($expected);
        self::assertSame($expected['UserID'], $userDto->id);
        self::assertSame($expected['Username'], $userDto->username);
        self::assertSame($expected['Password'], $userDto->password);
    }
}