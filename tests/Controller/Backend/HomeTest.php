<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;
session_start();
use App\Controller\Backend\Home;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    public function testAction(): void
    {

        $_SESSION['user'] = ['username' => 'maxmustermann', 'password' => '123'];
        $smartyView = new SmartyView(new \Smarty());
        $home = new Home($smartyView, new UserRepository(), new Redirect());
        $home->action();
        self::assertSame('maxmustermann', $smartyView->getParams()['username']);
        self::assertSame('backend/home.tpl', $smartyView->getTemplate());
        session_destroy();
    }
}