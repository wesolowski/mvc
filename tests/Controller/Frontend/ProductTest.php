<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Product;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use AppTest\Controller\RedirectMock;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected CategoryProductEntityManager $categoryProductEntityManager;
    protected Product $product;
    protected int $categoryId;

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
        $mappedCategory = $categoryMapper->map(['name' => 'ProductCategory']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->categoryId = $this->categoryRepository->getByName('ProductCategory')->id;

        $this->productEntityManager = $this->container->get(ProductEntityManager::class);
        $mappedProduct = $productMapper->map(['name' => 'Product', 'description' => 'Desc', 'categoryId' => $this->categoryId]);
        $this->productEntityManager->insert($mappedProduct);

        $this->product = new Product($this->container);
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
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $_GET['categoryId'] = $this->categoryId;
        $productId = $this->productRepository->getByName('Product')->id;

        $this->product->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('ProductCategory', $params['categoryDTO']->name);
        self::assertSame('Product', $params['productDTOList'][$productId]->name);
        self::assertSame('product.tpl', $viewInterface->getTemplate());
    }

    public function testActionNoCategoryId(): void
    {
        $this->product->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php', $redirect->url);
    }

    public function testActionEmptyCategoryId(): void
    {
        $_GET['categoryId'] = '';
        $this->product->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php', $redirect->url);
    }
}