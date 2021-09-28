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
use function PHPUnit\Framework\assertNull;

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

        $mappedCategory = $categoryMapper->map(['name' => 'CategoryDetail']);
        $this->categoryEntityManager->insert($mappedCategory);
        $mappedCategory = $categoryMapper->map(['name' => 'CategoryDetail2']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->categoryID = $this->categoryRepository->getByName('CategoryDetail2')->id;
        $mappedProduct = $productMapper->map(['name' => 'CategoryProductDetail2', 'description' => 'Desc2', 'categoryId' => $this->categoryID]);
        $this->productEntityManager->insert($mappedProduct);

        $this->categoryID = $this->categoryRepository->getByName('CategoryDetail')->id;
        $_GET['categoryId'] = (string)$this->categoryID;
        $mappedProduct = $productMapper->map(['name' => 'CategoryProductDetail', 'description' => 'Desc', 'categoryId' => $this->categoryID]);
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

        unset($_GET, $_POST);
    }

    public function testAction(): void
    {
        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $product = $this->productRepository->getByName('CategoryProductDetail');

        self::assertSame('CategoryDetail', $params['categoryDTO']->name);
        self::assertSame('CategoryProductDetail', $params['productDTOList'][$product->id]->name);
        self::assertSame('CategoryDetail', $params['updateName']);
        self::assertSame('backend/categoryDetail.tpl', $viewInterface->getTemplate());
    }

    public function testActionNoCategoryId(): void
    {
        unset($_GET);
        $this->categoryDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php?area=Admin&page=Category', $redirect->url);
    }

    public function testActionEmptyCategoryId(): void
    {
        $_GET['categoryId'] = '';
        $this->categoryDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);

        self::assertSame('index.php?area=Admin&page=Category', $redirect->url);
    }

    public function testActionUpdateCategory(): void{
        $_POST['updateCategory'] = true;
        $_POST['updateName'] = 'CategoryDetail_2';

        $this->categoryDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryId=' .  $this->categoryID, $redirect->url);
    }

    public function testActionUpdateCategoryTrim(): void{
        $_POST['updateCategory'] = true;
        $_POST['updateName'] = ' CategoryDetail_2 ';

        $this->categoryDetail->action();

        $categoryDTO = $this->categoryRepository->getByName('CategoryDetail_2');
        self::assertSame('CategoryDetail_2', $categoryDTO->name);
    }

    public function testActionUpdateCategoryNoCategoryGiven(): void
    {
        $_POST['updateCategory'] = true;
        $_POST['updateName'] = '';

        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Product Name musst be given', $params['error']['categoryDTO']);
    }

    public function testActionUpdateCategoryTrimName(): void
    {
        $_POST['updateCategory'] = true;
        $_POST['updateName'] = '   ';

        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Product Name musst be given', $params['error']['categoryDTO']);
    }

    public function testActionDeleteCategory(): void
    {
        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE categoryProduct');
        $connection->query('TRUNCATE product');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $_POST['deleteCategory'] = true;

        $this->categoryDetail->action();

        $redirect = $this->container->get(RedirectInterface::class);
        $categoryDTO = $this->categoryRepository->getByName('CategoryDetail');
        self::assertNull($categoryDTO);
        self::assertSame('index.php?area=Admin&page=Category', $redirect->url);
    }

    public function testActionCreateProduct(): void
    {
        $_POST['createProduct'] = true;
        $_POST['create']['name'] = 'ProductNew';
        $_POST['create']['price'] = 29.99;
        $_POST['create']['description'] = '';

        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $productRepository = $this->container->get(ProductRepository::class);
        $productID = $productRepository->getByName('ProductNew')->id;
        self::assertSame('ProductNew', $params['productDTOList'][$productID]->name);
    }

    public function testActionCreateProductNoPrice(): void
    {
        $_POST['createProduct'] = true;
        $_POST['create']['name'] = 'ProductNew';
        $_POST['create']['price'] = 'XYZ';
        $_POST['create']['description'] = '';

        $this->categoryDetail->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        var_dump($_POST);

        $productRepository = $this->container->get(ProductRepository::class);
        $productID = $productRepository->getByName('ProductNew')->id;
        self::assertSame('ProductNew', $params['productDTOList'][$productID]->name);
    }

    public function testActionCreateProductProductNameNotGiven(): void
    {
        $_POST['createProduct'] = true;
        $_POST['create']['name'] = '';

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

        self::assertSame('CategoryProductDetail2', $productList[$productTwo]->name);
        self::assertSame('CategoryProductDetail', $productList[$productOne]->name);
        self::assertSame('index.php?area=Admin&page=CategoryDetail&categoryId=' . $this->categoryID, $redirect->url);
    }
}