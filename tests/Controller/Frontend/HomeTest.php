<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Home;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    protected SmartyView $smartyView;
    protected Home $home;
    protected ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $this->productRepository = new ProductRepository();
        $this->home = new Home($this->smartyView, $this->productRepository, new Redirect());
    }

    public function testAction(){


        $this->home->action();
        $params = $this->smartyView->getParams();
        $list = $this->productRepository->getList();

        self::assertSame($list['10']->id, $params['productList']['10']->id);
        self::assertSame($list['2']->description, $params['productList']['2']->description);
        self::assertSame($list['4']->productname, $params['productList']['4']->productname);

        self::assertSame('home.tpl', $this->smartyView->getTemplate());
    }
}