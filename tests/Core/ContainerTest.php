<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainer(): void
    {
        $container = new Container();

        $class = new Container();

        $container->set(Container::class, $class);

        self::assertSame($class, $container->get(Container::class));
    }
}