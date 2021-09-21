<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\ProductDetail;
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
    protected ProductDetail $productDetail;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected CategoryEntityManager $categoryEntityManager;
    protected ProductEntityManager $productEntityManager;

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
        $this->productEntityManager = $this->container->get(ProductEntityManager::class);

        $mappedCategory = $categoryMapper->map(['CategoryName' => 'ProductDetailCategory']);
        $this->categoryEntityManager->insert($mappedCategory);
        $categoryID = $this->categoryRepository->getByName('ProductDetailCategory')->id;
        $_GET['categoryID'] = $categoryID;

        $mappedProduct = $productMapper->map(['ProductName' => 'ProductDetail', 'ProductDescription' => 'Desc', 'CategoryID' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);
        $productID = $this->productRepository->getByName('ProductDetail')->id;

        $this->productDetail = new ProductDetail($this->container);

        $_GET['productID'] = $productID;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $category = $this->categoryRepository->getByName('ProductDetailCategory');
        $product = $this->productRepository->getByName('ProductDetail');
        $this->productEntityManager->delete($product->id);
        $this->categoryEntityManager->delete($category->id);

        $this->database->disconnect();

        $_GET = [];
        $_POST = [];
    }

    public function testAction(): void
    {
        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $categoryID = $this->categoryRepository->getByName('ProductDetailCategory')->id;

        self::assertSame($categoryID, $params['categoryID']);
        self::assertSame('ProductDetail', $params['product']->productname);
        self::assertSame('ProductDetail', $params['editProduct']['name']);

        self::assertSame('backend/productDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionUpdateProductProductNameNotGiven(): void
    {
        $_POST['updateProduct'] = true;

        $this->productDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('', $params['editProduct']['name']);
        self::assertSame('', $params['editProduct']['description']);
        self::assertSame('Product name musst be given', $params['error']);
    }
}