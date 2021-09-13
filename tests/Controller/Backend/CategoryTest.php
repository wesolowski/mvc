<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\Category;
use App\Core\Redirect;
use App\Core\SmartyView;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected CategoryRepository $categoryRepository;
    protected Database $db;
    protected Category $category;
    protected SmartyView $smartyView;

    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION['user'] = ['username' => 'maxmustermann', 'password' => '123'];
        $this->db = new Database(['database' => 'MVC_Test']);
        $this->db->connect();

        $this->categoryRepository = new CategoryRepository($this->db);

        $repositoryType['categoryRepository'] = $this->categoryRepository;
        $repositoryType['userRepository'] = new UserRepository($this->db);
        $repositoryType['categoryEntityManager'] = new CategoryEntityManager($this->db, $this->categoryRepository);
        $redirect = new Redirect();
        $this->smartyView = new SmartyView(new \Smarty());

        $this->category = new Category($this->smartyView, $repositoryType, $redirect);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->db->disconnect();
        $_SESSION = [];

    }

    public function testAction(): void
    {
        $this->category->action();
        $params = $this->smartyView->getParams();

        self::assertSame(1, $params['categoryList'][1]->id);
        self::assertSame('Clothing', $params['categoryList'][3]->categoryname);

        self::assertSame('backend/category.tpl', $this->smartyView->getTemplate());

        $_GET = [];
    }
}