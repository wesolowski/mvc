<?php
declare(strict_types=1);

namespace AppTest\Controller;

use App\Controller\Login;
use App\Core\SmartyView;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\returnCallback;

class BackendLoginTest extends TestCase
{
    public function testAction(): void
    {
        $smartyView = new SmartyView(new \Smarty);
        $backendLogin = new Login($smartyView, new UserRepository());

        $_POST['login'] = true;
        $_POST['username'] = 'maxmustermann';
        $_POST['password'] = '234';

        $backendLogin->action();
        $params = $smartyView->getParams();

        self::assertSame("Password and User don't match", $params['errors'][0]);
        self::assertSame("maxmustermann", $params['username']);
        self::assertSame('backendLogin.tpl', $smartyView->getTemplate());

        unset($_POST['login'], $_POST['username'], $_POST['password']);
    }

    public function testLoginIsNotSet(): void
    {
        $smartyView = new SmartyView(new \Smarty);
        $backendLogin = new Login($smartyView, new UserRepository());

        $backendLogin->action();
        $params = $smartyView->getParams();

        self::assertSame("", $params['username']);
    }

    /*
    public function testRedirectToAdminArea(): void
    {
        $mock = $this->getMockBuilder(Login::class)
            ->setConstructorArgs([new SmartyView(new \Smarty) ,new UserRepository()])
            ->getMock();
        $mock->expects($this->any())
            ->method('action')
            ->will(
                $this->returnCallback(function (){
                    header('Location: Backend.php?page=BackendAdminPage');
                })
            );

        $_POST['login'] = true;
        $_POST['username'] = 'maxmustermann';
        $_POST['password'] = '234';

        $mock->action();
        $this->assertContains('Location: Backend.php?page=BackendAdminPage', xdebug_get_headers());
    }
    */

    public function testUserPasswordNotValid(): void
    {
        $backendLogin = new Login(new SmartyView(new \Smarty), new UserRepository());
        $user = [
            'username' => 'maxmustermann',
            'password' => '43543464'
        ];
        $errors = $backendLogin->validation($user);
        self::assertSame("Password and User don't match", $errors[0]);
    }

    /*
    public function testSessionNotSet(): void
    {

    }
    */
}