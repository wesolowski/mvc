<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

/*
use App\Controller\Frontend\Product;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Database;
use App\Model\Repository\ProductRepository;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected Product $category;
    protected SmartyView $smartyView;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $redirect = new Redirect();
        $this->db = new Database();
        $this->db->connect();
        $_GET['category'] = '3$Clothing';
        $repositoryType['productRepository'] = new ProductRepository(3, $this->db);
        $this->category = new Product($this->smartyView, $repositoryType, $redirect);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testAction(): void
    {
        $this->category->action();
        $param = $this->smartyView->getParams();
        self::assertSame('Clothing', $param['category']['categoryname']);
        self::assertSame('Basic Tee - White', $param['productList']['1']->productname);
        self::assertSame('product.tpl', $this->smartyView->getTemplate());

        $_GET = [];
    }
}
*/