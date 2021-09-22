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
use phpDocumentor\Reflection\Types\Context;
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

        $categoryMapper = $this->container->get(CategoryMapper::class);
        $this->categoryRepository = $this->container->get(CategoryRepository::class);
        $this->categoryEntityManager = $this->container->get(CategoryEntityManager::class);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'CategoryBackend']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->category = new Category($this->container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $category = $this->categoryRepository->getByName('CategoryBackend');
        $this->categoryEntityManager->delete($category->id);

        if( $_POST['newCategoryName'] === 'Test'){
            $categoryRepository = $this->container->get(CategoryRepository::class);
            $categoryEntityManager = $this->container->get(CategoryEntityManager::class);
            $category = $categoryRepository->getByName('Test');
            $categoryEntityManager->delete($category->id);
        }

        $_POST = [];
        $this->database->disconnect();
    }

    protected function testAction(): void
    {
        $this->category->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();
        $categoryID = $this->categoryRepository->getByName('CategoryBackend')->id;

        self::assertSame('Category', $params['categoryList'][$categoryID]->categoryname);
        self::assertSame('backend/category.tpl', $viewInterface->getTemplate());
    }

    public function testActionNewCategory(): void
    {
        $_POST['createCategory'] = true;
        $_POST['newCategoryName'] = 'Test';
        $this->category->action();
        $_POST['newCategoryName'] = 'Test';

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();
        $categoryID = $this->categoryRepository->getByName('Test')->id;

        self::assertSame('Test', $params['categoryList'][$categoryID]->categoryname);
    }

    public function testActionNoNewCategoryName(): void
    {
        $_POST['createCategory'] = true;
        $_POST['newCategoryName'] = '';

        $this->category->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Category Name musst be given', $params['error']);
    }
}