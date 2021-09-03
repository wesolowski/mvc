<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Detail;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class DetailTest extends TestCase
{
    protected SmartyView $smartyView;
    protected Detail $detail;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $redirect = new Redirect();
        $this->detail = new Detail($this->smartyView, new ProductRepository('Clothing', $redirect), $redirect);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
    }

    public function testAction():void
    {
        $_GET['id'] = '1';
        $_GET['category'] = 'Clothing';

        $this->detail->action();

        $params = $this->smartyView->getParams();

        self::assertSame('Shirt', $params['product']->productname);
        $_GET = [];
    }
}