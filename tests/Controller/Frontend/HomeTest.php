<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Home;
use App\Core\SmartyView;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    public function testAction(){
        $smartyView = new SmartyView(new \Smarty());
        $home = new Home($smartyView, new ProductRepository());
        $productRepository = new ProductRepository();

        $home->action();
        $params = $smartyView->getParams();
        $list = $productRepository->getList();

        self::assertSame($list['10']->id, $params['productList']['10']->id);
        self::assertSame($list['2']->description, $params['productList']['2']->description);
        self::assertSame($list['4']->productname, $params['productList']['4']->productname);

        self::assertSame('home.tpl', $smartyView->getTemplate());
    }
}