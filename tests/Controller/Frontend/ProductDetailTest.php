<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Product;
use App\Controller\Frontend\ProductDetail;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class ProductDetailTest extends TestCase
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

        $_GET['categoryID'] = '3';
        $_GET['productID'] = '1';
        $productDetail = new ProductDetail($this->container);
        $productDetail->action();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Clothing', $params['category']->categoryname);
        self::assertSame('Basic Tee - White', $params['product']->productname);

        self::assertSame('productDetail.tpl', $viewInterface->getTemplate());
    }
}