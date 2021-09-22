<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\Login;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use AppTest\Controller\RedirectMock;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Mapper\UserMapper;
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected Login $login;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);
        $this->container->set(RedirectInterface::class, new RedirectMock());

        $this->login = new Login($this->container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('TRUNCATE User');

        $_POST = [];
        $_SESSION = [];
        $this->database->disconnect();
    }

    public function testActionLoginSuccessfully(): void{
        $_POST['username'] = 'Kevin';
        $_POST['password'] = '123';
        $_POST['login'] = true;
        $userMapper = $this->container->get(UserMapper::class);
        $userEntityManager = $this->container->get(UserEntityManager::class);

        $mappedUser = $userMapper->map(['Username' => $_POST['username'], 'Password' => $_POST['password']]);
        $userEntityManager->insert($mappedUser);

        $this->login->action();

        self::assertSame('Kevin', $_SESSION['user']['username']);
        self::assertSame('123', $_SESSION['user']['password']);
    }

    public function testActionLoginFailed(): void{

        $_POST['username'] = '';
        $_POST['password'] = '';
        $_POST['login'] = true;

        $this->login->action();

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
