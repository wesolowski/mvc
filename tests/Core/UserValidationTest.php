<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\UserValidation;
use App\Model\Database;
use App\Model\Mapper\UserMapper;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserValidationTest extends TestCase
{
    protected UserRepository $userRepository;
    protected Database $database;
    protected UserValidation $userValidation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->userRepository = new UserRepository($this->database, new UserMapper());
        $this->userValidation = new UserValidation($this->userRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testValidation(): void
    {
        $user = [
            'username' => 'maxmustermann',
            'password' => '123'
        ];

        $actual = $this->userValidation->validation($user);

        self::assertEmpty($actual);
    }

    public function testValidationTrim(): void
    {
        $user = [
            'username' => ' maxmustermann  ',
            'password' => ' 123  '
        ];

        $actual = $this->userValidation->validation($user);

        self::assertEmpty($actual);
    }

    public function testValidationNoUserGiven(): void
    {
        $user = [
            'username' => '',
            'password' => ''
        ];

        $actual = $this->userValidation->validation($user);

        self::assertSame('User musst be given', $actual[0]);
        self::assertSame('Password musst be given', $actual[1]);
    }

    public function testValidationUserPasswordNotCorrect(): void
    {
        $user = [
            'username' => 'Test',
            'password' => '1'
        ];

        $actual = $this->userValidation->validation($user);

        self::assertSame('Password and User don\'t match', $actual[0]);
    }
}