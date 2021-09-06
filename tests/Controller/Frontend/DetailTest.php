<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Detail;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Database;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class DetailTest extends TestCase
{
    protected SmartyView $smartyView;
    protected Detail $detail;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $redirect = new Redirect();
        $this->db = new Database();
        $this->db->connect();
        $_GET['id'] = '1';
        $_GET['category'] = '3$Clothing';
        $this->detail = new Detail($this->smartyView, new ProductRepository('3$Clothing', $redirect, $this->db), $redirect);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $_GET = [];
        $this->db->disconnect();
    }

    public function testAction():void
    {


        $this->detail->action();

        $params = $this->smartyView->getParams();

        self::assertSame('Basic Tee - White', $params['product']->productname);
        $_GET = [];
    }
}