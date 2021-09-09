<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Database;

class UserEntityManagerTest extends TestCase
{
    protected UserEntityManager $userEntityManager;
    protected Database $database;
    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->userRepository = new UserRepository($this->database);
        $this->userEntityManager = new UserEntityManager($this->database, $this->userRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testInsertUser(): void
    {
        $this->userEntityManager->insert(['username' => 'Test', 'password' => '123']);

        $this->userRepository->map();
        $user = $this->userRepository->getByUsername('Test');

        self::assertSame('Test', $user->username);
        self::assertSame('123', $user->password);
    }

    public function testInsertUserUserPasswortNotGiven(): void
    {
        $actual = $this->userEntityManager->insert();

        self::assertSame('User and Password musst be given', $actual);
    }

    public function testUpdateUser(): void
    {
        $user = $this->userRepository->getByUsername('Test');

        $this->userEntityManager->update(['username' => $user->username, 'password' => '456', 'id' => $user->id]);

        $this->userRepository->map();
        $user = $this->userRepository->getByUsername('Test');

        self::assertSame('Test', $user->username);
        self::assertSame('456', $user->password);
    }

    public function testUpdateUserNoDataGiven(): void{
        $actual = $this->userEntityManager->update();

        self::assertSame('User, Password and ID musst be given', $actual);
    }

    public function testDeleteUser(): void
    {
        $user = $this->userRepository->getByUsername('Test');

        $this->userEntityManager->delete(['id' => $user->id]);

        $this->userRepository->map();

        self::assertNull($this->userRepository->getByUsername('Test'));
    }

    public function testDeleteUserIdNotGiven(): void
    {
        $actual = $this->userEntityManager->delete();

        self::assertSame('Id musst be given', $actual);
    }
}