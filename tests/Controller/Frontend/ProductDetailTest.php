<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Product;
use App\Controller\Frontend\ProductDetail;
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
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected ProductDetail $productDetail;
    protected int $categoryID;
    protected int $productID;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
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
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'ProductDetailCategory']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->categoryID = $this->categoryRepository->getByName('ProductDetailCategory')->id;

        $this->productEntityManager = $this->container->get(ProductEntityManager::class);
        $mappedProduct = $productMapper->map(['ProductName' => 'ProductDetail', 'ProductDescription' => 'Desc', 'CategoryID' => $this->categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $this->productID = $this->productRepository->getByName('ProductDetail')->id;

        $this->productDetail = new ProductDetail($this->container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE CategoryProduct');
        $connection->query('TRUNCATE Product');
        $connection->query('TRUNCATE Category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $_GET = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $_GET['categoryID'] = $this->categoryID;
        $_GET['productID'] = $this->productID;

        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('ProductDetailCategory', $params['category']->categoryname);
        self::assertSame('ProductDetail', $params['product']->productname);

        self::assertSame('productDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionCategoryIDNotGiven(): void
    {
        $this->productDetail->action();
        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php', $redirect->url);
    }

    public function testActionProductIDNotGiven(): void
    {
        $_GET['categoryID'] = $this->categoryID;

        $this->productDetail->action();
        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php?area=Consumer&page=Product&categoryID=' . $this->categoryID, $redirect->url);
    }
}