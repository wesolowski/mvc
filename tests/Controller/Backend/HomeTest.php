<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;
session_start();

use App\Controller\Backend\Home;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Database;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    protected Home $home;
    protected SmartyView $smartyView;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION['user'] = ['username' => 'maxmustermann', 'password' => '123'];
        $this->smartyView = new SmartyView(new \Smarty());
        $this->db = new Database();
        $this->db->connect();
        $repositoryType['userRepository'] = new UserRepository($this->db);
        $this->home = new Home($this->smartyView, $repositoryType , new Redirect());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_SESSION = [];
        $this->db->disconnect();
    }

    public function testAction(): void
    {
        $this->home->action();
        self::assertSame('maxmustermann', $this->smartyView->getParams()['username']);
        self::assertSame('backend/category.tpl', $this->smartyView->getTemplate());
    }
}