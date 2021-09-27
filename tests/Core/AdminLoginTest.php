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
        $this->db = new Database(['database' => 'MVC_Test']);
        $this->db->connect();

        $userMapper = new UserMapper();
        $userEntityManager = new UserEntityManager($this->db);

        $mappedUser = $userMapper->map(['Username' => 'maxmustermann', 'Password' => '123']);
        $userEntityManager->insert($mappedUser);

        $this->adminLogin = new AdminLogin(new UserRepository($this->db, $userMapper));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $connection = $this->db->getConnection();
        $connection->query('TRUNCATE User');
        $_SESSION = [];
        $this->db->disconnect();
    }

    public function testLoggendIn(): void
    {
        $_SESSION['user'] = ['username' => 'maxmustermann', 'password' => '123'];
        self::assertTrue($this->adminLogin->loggedIn());
    }

    public function testNotLoggedInSessionNotSet(): void
    {
        self::assertFalse($this->adminLogin->loggedIn());
    }

    public function testNotLoggedInUserPasswordWrong(): void
    {
        $_SESSION['user'] = ['username' => '', 'password' => ''];
        self::assertFalse($this->adminLogin->loggedIn());
    }
}