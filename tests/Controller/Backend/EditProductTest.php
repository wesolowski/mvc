<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\CategoryDetail;
use App\Controller\Backend\ProductDetail;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Database;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class EditProductTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected ProductDetail $editProduct;
    protected SmartyView $smartyView;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION['user'] = ['username' => 'maxmustermann', 'password' => '123'];
        $this->db = new Database(['database' => 'MVC_Test']);
        $this->db->connect();

        $_GET['category'] = '1$Media';
        $_GET['id'] = '5';
        $this->categoryRepository = new CategoryRepository($this->db);
        $this->productRepository = new ProductRepository(1, $this->db);

        $repositoryType['categoryRepository'] = $this->categoryRepository;
        $repositoryType['productRepository'] = $this->productRepository;
        $repositoryType['userRepository'] = new UserRepository($this->db);
        $repositoryType['productEntityManager'] = new ProductEntityManager($this->db, $this->productRepository, $this->categoryRepository);
        $redirect = new Redirect();
        $this->smartyView = new SmartyView(new \Smarty());

        $this->editProduct = new ProductDetail($this->smartyView, $repositoryType, $redirect);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
        $_SESSION = [];
        $_GET = [];
    }

    public function testAction(): void
    {

        $this->editProduct->action();

        $params = $this->smartyView->getParams();

        self::assertSame('Titanfall 2', $params['editProduct']['name']);
        self::assertSame('1$Media', $params['category']);
        self::assertSame('Titanfall 2', $params['product']->productname);

        self::assertSame('backend/editProduct.tpl', $this->smartyView->getTemplate());
    }
}