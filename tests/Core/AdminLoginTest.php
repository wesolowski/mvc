<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\AdminLogin;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class AdminLoginTest extends TestCase
{
    protected AdminLogin $adminLogin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminLogin = new AdminLogin(new UserRepository());
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