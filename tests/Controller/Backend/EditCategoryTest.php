<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use PHPUnit\Framework\TestCase;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use App\Model\Repository\UserRepository;
use App\Core\SmartyView;
use App\Core\Redirect;
use App\Model\Database;
use App\Controller\Backend\EditCategory;

class EditCategoryTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected EditCategory $editCategory;
    protected SmartyView $smartyView;
    protected ProductEntityManager $productEntityManager;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION['user'] = ['username' => 'maxmustermann', 'password' => '123'];
        $this->db = new Database(['database' => 'MVC_Test']);
        $this->db->connect();

        $_GET['category'] = '1$Media';
        $this->categoryRepository = new CategoryRepository($this->db);
        $this->productRepository = new ProductRepository('1$Media', $this->db);

        $repositoryType['categoryRepository'] = $this->categoryRepository;
        $repositoryType['productRepository'] = $this->productRepository;
        $repositoryType['userRepository'] = new UserRepository($this->db);
        $repositoryType['categoryEntityManager'] = new CategoryEntityManager($this->db, $this->categoryRepository);
        $this->productEntityManager = $repositoryType['productEntityManager'] = new ProductEntityManager($this->db, $this->productRepository, $this->categoryRepository);
        $redirect = new Redirect();
        $this->smartyView = new SmartyView(new \Smarty());

        $this->editCategory = new EditCategory($this->smartyView, $repositoryType, $redirect);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
        $_SESSION = [];
        $_GET = [];
    }

    public function testActionNoPost(): void
    {
        $this->editCategory->action();

        $getParams = $this->smartyView->getParams();
        $getTemp = $this->smartyView->getTemplate();
        $productList = $getParams['productList'];

        self::assertSame('backend/editCategory.tpl', $getTemp);
        self::assertSame('Media', $getParams['editCategoryName']);
        self::assertSame('Titanfall 2', $productList[5]->productname);
        self::assertSame('Mad Max - Fury Road', $getParams['productList'][6]->productname);
    }

    public function testActionCreateProduct(): void
    {
        $_POST['newProductName'] = 'Test123';
        $_POST['createProduct'] = true;
        $this->editCategory->action();

        $product = $this->productRepository->getByName('Test123');

        self::assertSame('Test123', $product->productname);

        $this->productEntityManager->delete($product->id);
    }
}