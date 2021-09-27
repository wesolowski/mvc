<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\AdminLogin;
use App\Model\Database;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Mapper\UserMapper;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class AdminLoginTest extends TestCase
{
    protected AdminLogin $adminLogin;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = new Database(['database' => 'mvc_test']);
        $this->db->connect();

        $userMapper = new UserMapper();
        $userEntityManager = new UserEntityManager($this->db);

        $mappedUser = $userMapper->map(['name' => 'maxmustermann', 'password' => '123']);
        $userEntityManager->insert($mappedUser);

        $this->adminLogin = new AdminLogin(new UserRepository($this->db, $userMapper));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $connection = $this->db->getConnection();
        $connection->query('TRUNCATE user');
        $_SESSION = [];
        $this->db->disconnect();
    }

    public function testLoggendIn(): void
    {
        $_SESSION['user'] = ['name' => 'maxmustermann', 'password' => '123'];
        self::assertTrue($this->adminLogin->loggedIn());
    }

    public function testNotLoggedInSessionNotSet(): void
    {
        self::assertFalse($this->adminLogin->loggedIn());
    }

    public function testNotLoggedInUserPasswordWrong(): void
    {
        $_SESSION['user'] = ['name' => '', 'password' => ''];
        self::assertFalse($this->adminLogin->loggedIn());
    }
}