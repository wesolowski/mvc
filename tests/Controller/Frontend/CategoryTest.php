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
    protected Category $category;

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
        $mappedCategory = $categoryMapper->map(['name' => 'CategoryFrontend']);
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
    }

    public function testAction(): void
    {
        $this->category->action();

        $category = $this->categoryRepository->getByName('CategoryFrontend');

        $viewInterface = $this->container->get(ViewInterface::class);
        $params = $viewInterface->getParams();

        self::assertSame('CategoryFrontend', $params['categoryDTOList'][$category->id]->name);
        self::assertSame('category.tpl', $viewInterface->getTemplate());
    }
}