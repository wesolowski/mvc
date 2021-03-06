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
    protected int $categoryId;
    protected int $productId;

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
        $mappedCategory = $categoryMapper->map(['name' => 'ProductDetailCategory']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->categoryId = $this->categoryRepository->getByName('ProductDetailCategory')->id;

        $this->productEntityManager = $this->container->get(ProductEntityManager::class);
        $mappedProduct = $productMapper->map(['name' => 'ProductDetail', 'description' => 'Desc', 'categoryId' => $this->categoryId]);
        $this->productEntityManager->insert($mappedProduct);

        $this->productId = $this->productRepository->getByName('ProductDetail')->id;

        $this->productDetail = new ProductDetail($this->container);
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

        unset($_GET);
    }

    public function testAction(): void
    {
        $_GET['categoryId'] = (string)$this->categoryId;
        $_GET['productId'] = (string)$this->productId;

        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('ProductDetailCategory', $params['categoryDTO']->name);
        self::assertSame('ProductDetail', $params['productDTO']->name);

        self::assertSame('productDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionNoCategoryId(): void
    {
        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php', $redirect->url);
    }

    public function testActionEmptyCategoryId(): void
    {
        $_GET['categoryId'] = '';

        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php', $redirect->url);
    }

    public function testActionCategoryNotFound(): void
    {
        $_GET['categoryId'] = '10';

        $this->productDetail->action();
        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php', $redirect->url);
    }

    public function testActionProductIDNotGiven(): void
    {
        $_GET['categoryId'] = (string)$this->categoryId;
        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php?area=Consumer&page=Product&categoryId=' . $this->categoryId, $redirect->url);
    }

    public function testActionProductNotFound(): void
    {
        $_GET['categoryId'] = $categoryId = (string)$this->categoryId;
        $_GET['productId'] = '10';

        $this->productDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php?area=Consumer&page=Product&categoryId=' . $categoryId, $redirect->url);
    }
}