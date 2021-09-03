<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Category;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected Category $category;
    protected SmartyView $smartyView;


    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $redirect = new Redirect();
        $this->category = new Category($this->smartyView, new ProductRepository('Clothing', $redirect), $redirect);
    }

    public function testAction(): void
    {
        $_GET['category'] = 'Clothing';
        $this->category->action();
        $param = $this->smartyView->getParams();
        self::assertSame('Clothing', $param['category']);
        self::assertSame('Shirt', $param['productList']['1']->productname);
        self::assertSame('category.tpl', $this->smartyView->getTemplate());
    }
}