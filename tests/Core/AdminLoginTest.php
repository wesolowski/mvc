<?php
declare(strict_types=1);

namespace AppTest\Core;
/*
use App\Core\AdminLogin;
use App\Model\Database;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class AdminLoginTest extends TestCase
{
    protected AdminLogin $adminLogin;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->db = new Database();
        $this->db->connect();
        $this->adminLogin = new AdminLogin(new UserRepository($this->db));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
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
*/