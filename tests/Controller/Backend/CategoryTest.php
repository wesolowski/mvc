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
        $this->database = new Database(['database' => 'mvc_test']);
        $this->database->connect();
        $this->container = new Container();
        $dependencyProvider = new DependencyProvider();
        $dependencyProvider->provide($this->container, $this->database);

        $categoryMapper = $this->container->get(CategoryMapper::class);
        $this->categoryRepository = $this->container->get(CategoryRepository::class);
        $this->categoryEntityManager = $this->container->get(CategoryEntityManager::class);
        $mappedCategory = $categoryMapper->map(['name' => 'CategoryBackend']);
        $this->categoryEntityManager->insert($mappedCategory);

        $this->category = new Category($this->container);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $connection = $this->database->getConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query('TRUNCATE category');
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');

        $_POST = [];
    }

    public function testAction(): void
    {
        $this->category->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();
        $categoryID = $this->categoryRepository->getByName('CategoryBackend')->id;

        self::assertSame('CategoryBackend', $params['categoryDTOList'][$categoryID]->name);
        self::assertSame('backend/category.tpl', $viewInterface->getTemplate());
    }

    public function testActionNewCategory(): void
    {
        $_POST['createCategory'] = true;
        $_POST['createName'] = 'Test';
        $this->category->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();
        $categoryID = $this->categoryRepository->getByName('Test')->id;

        self::assertSame('Test', $params['categoryDTOList'][$categoryID]->name);
    }

    public function testActionNoNewCategoryName(): void
    {
        $_POST['createCategory'] = true;
        $_POST['createName'] = '';

        $this->category->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Category Name musst be given', $params['error']);
    }

    public function testActionTrimNewCategoryName(): void
    {
        $_POST['createCategory'] = true;
        $_POST['createName'] = '   ';

        $this->category->action();

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Category Name musst be given', $params['error']);
    }
}