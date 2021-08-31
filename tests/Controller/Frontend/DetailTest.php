<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Detail;
use App\Core\SmartyView;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class DetailTest extends TestCase
{
    public function testAction():void
    {
        $smartyView = new SmartyView(new \Smarty());
        $detail = new Detail($smartyView, new ProductRepository());
        $productRepository = new ProductRepository();
        $_GET['id'] = '10';

        $detail->action();

        $params = $smartyView->getParams();

        self::assertSame('Shirt', $params['product']->productname);
        unset($_GET['id']);
    }
    public function ActionRedirect():void
    {

    }
}