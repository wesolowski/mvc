<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\CategoryDetail;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class CategoryDetailTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected CategoryDetail $categoryDetail;

    protected function setUp(): void
    {
        parent::setUp();

        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $this->categoryDetail = new CategoryDetail($this->container);

        $_GET['categoryID'] = 3;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if(isset($_POST['createProduct'])){
            $productEntityManager = $this->container->get(ProductEntityManager::class);
            $productRepository = $this->container->get(ProductRepository::class);
            $product = $productRepository->getByName('Test');
            $productEntityManager->delete($product->id);
        }

        $_POST = [];
        $_GET = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Clothing', $params['category']->categoryname);
        //self::assertSame('Basic Tee - White', $params['productList'][1]->productname);
        self::assertSame('Titanfall 2', $params['productListExcludeCategory'][5]->productname);
        self::assertSame('Clothing', $params['editCategoryName']);

        self::assertSame('backend/categoryDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionUpdateCategoryNoCategoryGiven(): void
    {
        $_POST['updateCategory'] = true;
        $_POST['editCategoryName'] = '';

        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Product Name musst be given', $params['error']['category']);
    }

    public function testActionCreateProduct(): void
    {
        $_POST['createProduct'] = true;
        $_POST['newProductName'] = 'Test';
        $_POST['newProductDescription'] = '';

        $this->categoryDetail->action();

        $_POST['createProduct'] = true;

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $productRepository = $this->container->get(ProductRepository::class);
        $productID = $productRepository->getByName('Test')->id;
        self::assertSame('Test', $params['productList'][$productID]->productname);
    }

    public function testActionCreateProductProductNameNotGiven(): void
    {
        $_POST['createProduct'] = true;
        $_POST['newProductName'] = '';

        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Product Name musst be given', $params['error']['product']);
    }
}