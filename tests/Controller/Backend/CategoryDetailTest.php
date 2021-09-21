<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\CategoryDetail;
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

class CategoryDetailTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected CategoryDetail $categoryDetail;
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

        $mappedCategory = $categoryMapper->map(['CategoryName' => 'CategoryDetail']);
        $this->categoryEntityManager->insert($mappedCategory);

        $categoryID = $this->categoryRepository->getByName('CategoryDetail')->id;
        $_GET['categoryID'] = $categoryID;

        $mappedProduct = $productMapper->map(['ProductName' => 'CategoryProductDetail', 'ProductDescription' => 'Desc', 'CategoryID' => $categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $this->categoryDetail = new CategoryDetail($this->container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if(isset($_POST['createProduct'])){
            $productEntityManager = $this->container->get(ProductEntityManager::class);
            $productRepository = $this->container->get(ProductRepository::class);
            $product = $productRepository->getByName('ProductNew');
            $productEntityManager->delete($product->id);
        }

        $category = $this->categoryRepository->getByName('CategoryDetail');
        $product = $this->productRepository->getByName('CategoryProductDetail');
        $this->productEntityManager->delete($product->id);
        $this->categoryEntityManager->delete($category->id);

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
}