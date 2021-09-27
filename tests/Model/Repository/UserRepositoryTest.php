<?php
declare(strict_types=1);

namespace AppTest\Model\Repository;

use App\Model\Database;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Mapper\UserMapper;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected UserRepository $userRepository;
    protected UserEntityManager $userEntityManager;
    protected Database $database;

    protected function setUp(): void
    {
        parent::setUp();
        $db = $this->database = new Database(['database' => 'mvc_test']);
        $db->connect();
        $this->userRepository = new UserRepository($db, new UserMapper());
        $this->userEntityManager = new UserEntityManager($db);

        $userMapper = new UserMapper();
        $mappedUser = $userMapper->map(['name' => 'Test', 'password' => '123']);
        $this->userEntityManager->insert($mappedUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $connection = $this->database->getConnection();
        $connection->query('TRUNCATE user');
        $this->database->disconnect();
    }

    public function testGetByName(): void
    {
        $userTransferObject = $this->userRepository->getByUsername('Test');

        self::assertSame('Test', $userTransferObject->name);
        self::assertSame('123', $userTransferObject->password);
    }

    public function testGetByID(): void
    {
        $user = $this->userRepository->getByUsername('Test');
        $userTransferObject = $this->userRepository->getByID($user->id);

        self::assertSame('Test', $userTransferObject->name);
        self::assertSame('123', $userTransferObject->password);
    }
}