<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\CategoryDetail;
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

class CategoryDetailTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected CategoryDetail $categoryDetail;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected int $categoryID;

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

        $mappedCategory = $categoryMapper->map(['CategoryName' => 'CategoryDetail']);
        $this->categoryEntityManager->insert($mappedCategory);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'CategoryDetail2']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->categoryID = $this->categoryRepository->getByName('CategoryDetail2')->id;
        $mappedProduct = $productMapper->map(['ProductName' => 'CategoryProductDetail2', 'ProductDescription' => 'Desc2', 'CategoryID' => $this->categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $this->categoryID = $this->categoryRepository->getByName('CategoryDetail')->id;
        $_GET['categoryID'] = (string)$this->categoryID;
        $mappedProduct = $productMapper->map(['ProductName' => 'CategoryProductDetail', 'ProductDescription' => 'Desc', 'CategoryID' => $this->categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $this->categoryDetail = new CategoryDetail($this->container);
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

        $_POST = [];
        $_GET = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $product = $this->productRepository->getByName('CategoryProductDetail');

        self::assertSame('CategoryDetail', $params['category']->categoryname);
        self::assertSame('CategoryProductDetail', $params['productList'][$product->id]->productname);
        self::assertSame('CategoryDetail', $params['editCategoryName']);

        //self::assertSame(' ', $params['productListExcludeCategory'][5]->productname);

        self::assertSame('backend/categoryDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionUpdateCategory(): void{
        $_POST['updateCategory'] = true;
        $_POST['editCategoryName'] = 'CategoryDetail_2';

        $this->categoryDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryID=' .  $this->categoryID, $redirect->url);
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

    public function testActionDeleteCategory(): void
    {
        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE CategoryProduct');
        $connection->query('TRUNCATE Product');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $_POST['deleteCategory'] = true;

        $this->categoryDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=Category', $redirect->url);
    }

    public function testActionCreateProduct(): void
    {
        $_POST['createProduct'] = true;
        $_POST['newProductName'] = 'ProductNew';
        $_POST['newProductDescription'] = '';

        $this->categoryDetail->action();

        $_POST['createProduct'] = true;

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $productRepository = $this->container->get(ProductRepository::class);
        $productID = $productRepository->getByName('ProductNew')->id;
        self::assertSame('ProductNew', $params['productList'][$productID]->productname);
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

    public function testActionAddProduct(): void
    {
        $_POST['addProduct'] = true;
        $_POST['selectProduct'] = (string)$this->productRepository->getByName('CategoryProductDetail2')->id;
        $productTwo = (int)$_POST['selectProduct'];
        $productOne = $this->productRepository->getByName('CategoryProductDetail')->id;
        $redirect = $this->container->get(RedirectInterface::class);

        $this->categoryDetail->action();
        $productList = $this->productRepository->getList();

        self::assertSame('CategoryProductDetail2', $productList[$productTwo]->productname);
        self::assertSame('CategoryProductDetail', $productList[$productOne]->productname);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryID=' . $this->categoryID, $redirect->url);
    }
}