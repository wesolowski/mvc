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
            'id' => 1,
            'name' => 'maxmusterman',
            'password' => '123456789'
        ];
        $userDto = $userMapper->map($expected);
        self::assertSame($expected['id'], $userDto->id);
        self::assertSame($expected['name'], $userDto->username);
        self::assertSame($expected['password'], $userDto->password);
    }
}