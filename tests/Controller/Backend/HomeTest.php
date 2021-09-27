<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\Home;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    protected Database $database;
    protected Container $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $_SESSION['user']['username'] = 'Test';

        $home = new Home($this->container);
        $home->action();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();
        self::assertSame('?area=Admin&page=Logout', $params['footerLink']['link']);
        self::assertSame('Admin - Logout', $params['footerLink']['name']);
        self::assertSame('Test', $params['username']);
        self::assertSame('backend/home.tpl', $viewInterface->getTemplate());
    }
}