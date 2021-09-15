<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\ProductDetail;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class ProductDetailTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected ProductDetail $productDetail;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $this->productDetail = new ProductDetail($this->container);

        $_GET['categoryID'] = 3;
        $_GET['productID'] = 1;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
        $_POST = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame(3, $params['categoryID']);
        self::assertSame('Basic Tee - White', $params['product']->productname);
        self::assertSame('Basic Tee - White', $params['editProduct']['name']);

        self::assertSame('backend/productDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionUpdateProductProductNameNotGiven(): void
    {
        $_POST['updateProduct'] = true;

        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('', $params['editProduct']['name']);
        self::assertNull($params['editProduct']['description']);
        self::assertSame('Product name musst be given', $params['error']);
    }
}