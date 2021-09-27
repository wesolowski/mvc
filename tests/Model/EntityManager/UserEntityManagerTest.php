<?php
declare(strict_types=1);

namespace AppTest\Model\EntityManager;

use App\Model\Dto\UserDataTransferObject;
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
    protected UserDataTransferObject $userDTO;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();
        $this->userMapper = new UserMapper();
        $this->userRepository = new UserRepository($this->database, $this->userMapper);
        $this->userEntityManager = new UserEntityManager($this->database);

        $userDTO = $this->userMapper->map(['name' => 'Test', 'password' => '123']);
        $this->userEntityManager->insert($userDTO);
        $this->userDTO = $this->userRepository->getByUsername('Test');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();

        $connection->query('TRUNCATE user');

        $this->database->disconnect();
    }

    public function testInsertUser(): void
    {
        self::assertSame('Test', $this->userDTO->name);
        self::assertSame('123', $this->userDTO->password);
    }

    public function testUpdateUser(): void
    {
        $userDTO = $this->userMapper->map(['name' => $this->userDTO->name, 'password' => '456', 'id' => $this->userDTO->id]);
        $this->userEntityManager->update($userDTO);

        $this->userDTO = $this->userRepository->getByUsername('Test');

        self::assertSame('Test', $this->userDTO->name);
        self::assertSame('456', $this->userDTO->password);
    }

    public function testDeleteUser(): void
    {
        $this->userEntityManager->delete($this->userDTO->id);
        self::assertNull($this->userRepository->getByUsername('Test'));
    }
}