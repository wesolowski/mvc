<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Product;
use App\Controller\Frontend\ProductDetail;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class ProductDetailTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $categoryMapper = $this->container->get(CategoryMapper::class);
        $productMapper = $this->container->get(ProductMapper::class);
        $this->categoryRepository = $this->container->get(CategoryRepository::class);
        $this->productRepository = $this->container->get(ProductRepository::class);

        $this->categoryEntityManager = $this->container->get(CategoryEntityManager::class);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'ProductCategory']);
        $this->categoryEntityManager->insert($mappedCategory);

        $categoryID = $this->categoryRepository->getByName('ProductCategory')->id;

        $this->productEntityManager = $this->container->get(ProductEntityManager::class);
        $mappedProduct = $productMapper->map(['ProductName' => 'ProductDetail', 'ProductDescription' => 'Desc', 'CategoryID' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $productID = $this->productRepository->getByName('ProductDetail')->id;

        $_GET['categoryID'] = $categoryID;
        $_GET['productID'] = $productID;
        $productDetail = new ProductDetail($this->container);
        $productDetail->action();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $category = $this->categoryRepository->getByName('ProductCategory');
        $product = $this->productRepository->getByName('ProductDetail');

        $this->productEntityManager->delete($product->id);
        $this->categoryEntityManager->delete($category->id);

        $_GET = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('ProductCategory', $params['category']->categoryname);
        self::assertSame('ProductDetail', $params['product']->productname);

        self::assertSame('productDetail.tpl', $viewInterface->getTemplate());
    }
}