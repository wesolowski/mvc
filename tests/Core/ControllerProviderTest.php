<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Controller\Backend\Login;
use App\Controller\Backend\Home as BackendHome;
use App\Controller\Frontend\Home;
use App\Controller\Frontend\Category;
use App\Controller\Frontend\Detail;
use App\Core\ControllerProvider;
use PHPUnit\Framework\TestCase;

class ControllerProviderTest extends TestCase
{
    protected ControllerProvider $controllerProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controllerProvider = new ControllerProvider();
    }

    public function testGetFrontendList(): void{
        $actual = $this->controllerProvider->getFrontendList();

        self::assertSame(Category::class, $actual[0]);
        self::assertSame(Detail::class, $actual[1]);
        self::assertSame(Home::class, $actual[2]);
    }

    public function testGetBackendList(): void{
        $actual = $this->controllerProvider->getBackendList();

        self::assertSame(Login::class, $actual[0]);
        self::assertSame(BackendHome::class, $actual[1]);
    }
}