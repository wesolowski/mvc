<?php
declare(strict_types=1);

namespace AppTest\Controller\Frontend;

use App\Controller\Frontend\Category;
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


        $categoryMapper = new CategoryMapper();
        $this->categoryRepository = new CategoryRepository($this->database, $categoryMapper);

        $this->categoryEntityManager = new CategoryEntityManager($this->database);
        $mappedCategory = $categoryMapper->map(['CategoryName' => 'Test']);
        $this->categoryEntityManager->insert($mappedCategory);

        $category = new Category($this->container);
        $category->action();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $categoryID = $this->categoryRepository->getByName('Test')->id;

        $this->categoryEntityManager->delete($categoryID);

        $this->database->disconnect();
    }

    public function testAction(): void
    {
        $categoryID = $this->categoryRepository->getByName('Test')->id;

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('Test', $params['categoryList'][$categoryID]->categoryname);

        self::assertSame('category.tpl', $viewInterface->getTemplate());
    }
}