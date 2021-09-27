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
        $this->database = new Database(['database' => 'mvc_test']);
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
        $connection->query('TRUNCATE user');

        $_POST = [];
        $_SESSION = [];
        $this->database->disconnect();
    }

    public function testActionLoginSuccessfully(): void{
        $_POST['name'] = 'Kevin';
        $_POST['password'] = '123';
        $_POST['login'] = true;
        $userMapper = $this->container->get(UserMapper::class);
        $userEntityManager = $this->container->get(UserEntityManager::class);
        $redirect = $this->container->get(RedirectInterface::class);

        $mappedUser = $userMapper->map(['name' => $_POST['name'], 'password' => $_POST['password']]);
        $userEntityManager->insert($mappedUser);

        $this->login->action();

        self::assertSame('index.php?area=Admin&page=Home', $redirect->url);
        self::assertSame('Kevin', $_SESSION['user']['name']);
        self::assertSame('123', $_SESSION['user']['password']);
    }

    public function testActionLoginFailed(): void{

        $_POST['name'] = '';
        $_POST['password'] = '';
        $_POST['login'] = true;

        $this->login->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('', $params['name']);
        self::assertSame('Username musst be given', $params['errors'][0]);
        self::assertSame('Password musst be given', $params['errors'][1]);
        self::assertSame(' ', $params['footerLink']['link']);
        self::assertSame('Public - Category', $params['footerLink']['name']);

        self::assertSame('backend/login.tpl', $viewInterface->getTemplate());
    }
}
