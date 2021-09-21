<?php
declare(strict_types=1);

namespace AppTest\Controller\Backend;

use App\Controller\Backend\Category;
use App\Controller\Backend\Home;
use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Repository\CategoryRepository;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected Database $database;
    protected Container $container;
    protected Category $category;
    protected CategoryEntityManager $categoryEntityManager;
    protected CategoryRepository $categoryRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $_POST['createCategory'] = true;

        $categoryMapper = new CategoryMapper();
        $this->categoryRepository = new CategoryRepository($this->database, $categoryMapper);
        $this->categoryEntityManager = new CategoryEntityManager($this->database);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'Category']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->category = new Category($this->container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if( $_POST['newCategoryName'] === 'Test'){
            $categoryRepository = $this->container->get(CategoryRepository::class);
            $categoryEntityManager = $this->container->get(CategoryEntityManager::class);
            $category = $categoryRepository->getByName('Test');
            $categoryEntityManager->delete($category->id);
        }

        $categoryID = $this->categoryRepository->getByName('Category')->id;
        $this->categoryEntityManager->delete($categoryID);

        $_POST = [];
        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $_POST['newCategoryName'] = 'Test';
        $this->category->action();
        $_POST['newCategoryName'] = 'Test';

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        $categoryID = $this->categoryRepository->getByName('Category')->id;

        self::assertSame('Category', $params['categoryList'][$categoryID]->categoryname);
        self::assertSame('backend/category.tpl', $viewInterface->getTemplate());


    }

    public function testActionNoNewCategoryName(): void
    {
        $_POST['newCategoryName'] = '';
        $this->category->action();
        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Category Name musst be given', $params['error']);
    }
}