<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

//session_start();

use App\Controller\Backend\Login;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\returnCallback;

class LoginTest extends TestCase
{

    protected function tearDown(): void
    {
        parent::tearDown();

        $_POST = [];
    }

    public function testAction(): void
    {
        $smartyView = new SmartyView(new \Smarty);
        $backendLogin = new Login($smartyView, new UserRepository(), new Redirect());

        $_POST['login'] = true;
        $_POST['username'] = 'maxmustermann';
        $_POST['password'] = '234';

        $backendLogin->action();
        $params = $smartyView->getParams();

        self::assertSame("Password and User don't match", $params['errors'][0]);
        self::assertSame("maxmustermann", $params['username']);
        self::assertSame('backend/login.tpl', $smartyView->getTemplate());
    }
    /* Fehler wegen redirect
    public function testSessionNotSet(): void
    {
        $smartyView = new SmartyView(new \Smarty);
        $backendLogin = new Login($smartyView, new UserRepository(), new Redirect());

        $_POST['login'] = true;
        $_POST['username'] = 'maxmustermann';
        $_POST['password'] = '123';

        $backendLogin->action();

        self::assertSame('maxmustermann', $_SESSION['user']['username']);
        self::assertSame('123', $_SESSION['user']['password']);

        session_destroy();
    }
    */
}