<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

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
        self::assertSame('backendLogin.tpl', $smartyView->getTemplate());
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
    public function testSessionNotSet(): void
    {

    }
    */
}