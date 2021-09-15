<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\Login;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected Database $database;
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $_POST['username'] = '';
        $_POST['password'] = '';
        $_POST['login'] = true;

        $login = new Login($this->container);
        $login->action();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_POST = [];
        $this->database->disconnect();
    }

    public function testAction(): void{
        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('', $params['username']);
        self::assertSame('User musst be given', $params['errors'][0]);
        self::assertSame('Password musst be given', $params['errors'][1]);
        self::assertSame(' ', $params['footerLink']['link']);
        self::assertSame('Public - Category', $params['footerLink']['name']);

        self::assertSame('backend/login.tpl', $viewInterface->getTemplate());
    }
}