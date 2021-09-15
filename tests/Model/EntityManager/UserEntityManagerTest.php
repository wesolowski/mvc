<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Model\Mapper\UserMapper;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Database;

class UserEntityManagerTest extends TestCase
{
    protected UserEntityManager $userEntityManager;
    protected Database $database;
    protected UserRepository $userRepository;
    protected UserMapper $userMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepository($this->database, $this->userMapper);
        $this->userEntityManager = new UserEntityManager($this->database);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testInsertUser(): void
    {
        $mappedUser = $this->userMapper->map(['Username' => 'Test', 'Password' => '123']);

        $this->userEntityManager->insert($mappedUser);

        $user = $this->userRepository->getByUsername('Test');

        self::assertSame('Test', $user->username);
        self::assertSame('123', $user->password);
    }

    public function testUpdateUser(): void
    {
        $user = $this->userRepository->getByUsername('Test');

        $mappedUser = $this->userMapper->map(['Username' => $user->username, 'Password' => '456', 'UserID' => $user->id]);

        $this->userEntityManager->update($mappedUser);

        $user = $this->userRepository->getByUsername('Test');

        self::assertSame('Test', $user->username);
        self::assertSame('456', $user->password);
    }

    public function testDeleteUser(): void
    {
        $user = $this->userRepository->getByUsername('Test');

        $this->userEntityManager->delete($user->id);

        self::assertNull($this->userRepository->getByUsername('Test'));
    }
}