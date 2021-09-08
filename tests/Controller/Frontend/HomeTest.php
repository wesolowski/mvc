<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Home;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Repository\CategoryRepository;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class HomeTest extends TestCase
{
    protected SmartyView $smartyView;
    protected Home $home;
    protected CategoryRepository $categoryRepository;
    protected Database $db;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smartyView = new SmartyView(new \Smarty());
        $this->db = new Database();
        $this->db->connect();
        $this->categoryRepository = new CategoryRepository($this->db);
        $repositoryType['categoryRepository'] = $this->categoryRepository;
        $this->home = new Home($this->smartyView, $repositoryType);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
    }

    public function testAction(){


        $this->home->action();
        $params = $this->smartyView->getParams();
        $list = $this->categoryRepository->getList();

        self::assertSame('1', $params['categoryList']['1']->id);
        self::assertSame('Clothing', $params['categoryList']['3']->categoryname);

        self::assertSame('home.tpl', $this->smartyView->getTemplate());
    }
}