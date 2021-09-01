<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Home;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\CategoryRepository;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    protected SmartyView $smartyView;
    protected Home $home;
    protected CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $this->categoryRepository = new CategoryRepository();
        $this->home = new Home($this->smartyView, $this->categoryRepository, new Redirect());
    }

    public function testAction(){


        $this->home->action();
        $params = $this->smartyView->getParams();
        $list = $this->categoryRepository->getList();

        self::assertSame($list['1']->id, $params['categoryList']['1']->id);
        self::assertSame($list['2']->description, $params['categoryList']['2']->description);
        self::assertSame($list['3']->productname, $params['categoryList']['3']->productname);

        self::assertSame('home.tpl', $this->smartyView->getTemplate());
    }
}