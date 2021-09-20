<?php
declare(strict_types=1);

namespace AppTest\Core\Provider;

use App\Core\Container;
use App\Core\Provider\DependencyProvider;
use App\Model\Database;
use PHPUnit\Framework\TestCase;

class DependencyProviderTest extends TestCase
{
    protected Container $container;
    protected Database $database;
    protected DependencyProvider $dependencyProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->database = new Database(['database' => 'MVC_Test']);
        $this->database->connect();
        $this->container = new Container();
        $this->dependencyProvider = new DependencyProvider();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->database->disconnect();
    }

    public function testProvide(): void
    {
        $this->dependencyProvider->provide($this->container, $this->database);
        $objectList = $this->container->getList();
        self::assertCount(14, $objectList);
    }
}