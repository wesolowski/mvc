<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

//session_start();

use App\Controller\Backend\Login;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Database;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\returnCallback;

class LoginTest extends TestCase
{
    protected SmartyView $smartyView;
    protected Login $backendLogin;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty);
        $this->db = new Database();
        $this->db->connect();
        $repositoryType['userRepository'] = new UserRepository($this->db);
        $this->backendLogin = new Login($this->smartyView, $repositoryType, new Redirect());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_POST = [];
        $this->db->disconnect();
    }

    public function testAction(): void
    {
        $_POST['login'] = true;
        $_POST['username'] = 'maxmustermann';
        $_POST['password'] = '234';

        $this->backendLogin->action();
        $params = $this->smartyView->getParams();

        self::assertSame("Password and User don't match", $params['errors'][0]);
        self::assertSame("maxmustermann", $params['username']);
        self::assertSame('backend/login.tpl', $this->smartyView->getTemplate());
    }
}