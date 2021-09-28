<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\ProductDetail;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use AppTest\Controller\RedirectMock;
use PHPUnit\Framework\TestCase;

class ProductDetailTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected ProductDetail $productDetail;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected int $categoryID;
    protected int $productID;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);
        $this->container->set(RedirectInterface::class, new RedirectMock());

        $categoryMapper = $this->container->get(CategoryMapper::class);
        $productMapper = $this->container->get(ProductMapper::class);
        $this->categoryRepository = $this->container->get(CategoryRepository::class);
        $this->productRepository = $this->container->get(ProductRepository::class);
        $this->categoryEntityManager = $this->container->get(CategoryEntityManager::class);
        $this->productEntityManager = $this->container->get(ProductEntityManager::class);

        $mappedCategory = $categoryMapper->map(['name' => 'ProductDetailCategory']);
        $this->categoryEntityManager->insert($mappedCategory);
        $this->categoryID = $this->categoryRepository->getByName('ProductDetailCategory')->id;
        $_GET['categoryId'] = $this->categoryID;

        $mappedProduct = $productMapper->map(['name' => 'ProductDetail', 'description' => 'Desc', 'categoryId' => $this->categoryID]);
        $this->productEntityManager->insert($mappedProduct);
        $this->productID = $this->productRepository->getByName('ProductDetail')->id;

        $this->productDetail = new ProductDetail($this->container);

        $_GET['productId'] = $this->productID;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE categoryProduct');
        $connection->query('TRUNCATE product');
        $connection->query('TRUNCATE category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $this->database->disconnect();

        unset($_GET, $_POST);
    }

    public function testAction(): void
    {
        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $categoryID = $this->categoryRepository->getByName('ProductDetailCategory')->id;

        self::assertSame($categoryID, $params['categoryId']);
        self::assertSame('ProductDetail', $params['product']->productname);
        self::assertSame('ProductDetail', $params['editProduct']['name']);

        self::assertSame('backend/productDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionCategoryIDNotGiven(): void
    {
        unset($_GET);
        $_POST['updateProduct'] = true;
        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=Category', $redirect->url);
    }

    public function testActionProductIDNotGiven(): void
    {
        unset($_GET);
        $_GET['categoryId'] = 1;
        $_POST['updateProduct'] = true;


        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryId=1', $redirect->url);
    }

    public function testActionUpdateProductProductNameNotGiven(): void
    {
        $_POST['updateProduct'] = true;

        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Product name musst be given', $params['error']);
    }

    public function testActionUpdateProduct(): void
    {
        $_POST['updateProduct'] = true;
        $_POST['editProductName'] = 'EditProduct';

        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=ProductDetail&categoryId=' . $this->categoryID . '&productId=' . $this->productID, $redirect->url);
    }

    public function testActionDeleteProduct(): void
    {
        $_POST['deleteProduct'] = true;

        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryId=' . $this->categoryID, $redirect->url);
    }

    public function testActionRemoveProductFromCategory(): void
    {
        $_POST['removeProductFromCategory'] = true;

        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryId=' . $this->categoryID, $redirect->url);
    }
}