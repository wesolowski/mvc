<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Product;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
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
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $_GET['categoryID'] = 3;
        $product = new Product($this->container);
        $product->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Clothing', $params['category']->categoryname);
        self::assertSame('Basic Tee - White', $params['productList'][1]->productname);

        self::assertSame('product.tpl', $viewInterface->getTemplate());
    }
}