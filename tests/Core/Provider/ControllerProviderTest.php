<?php
declare(strict_types=1);

namespace AppTest\Core\Provider;

use App\Core\Provider\ControllerProvider;
use App\Controller\Backend\CategoryDetail;
use App\Controller\Backend\Home;
use App\Controller\Backend\Login;
use App\Controller\Backend\Logout;
use App\Controller\Frontend\Category;
use App\Controller\Frontend\Product;
use App\Controller\Frontend\ProductDetail;
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
        self::assertSame(Product::class, $actual[1]);
        self::assertSame(ProductDetail::class, $actual[2]);
    }

    public function testGetBackendList(): void{
        $actual = $this->controllerProvider->getBackendList();

        self::assertSame(Login::class, $actual[0]);
        self::assertSame(Logout::class, $actual[1]);
        self::assertSame(Home::class, $actual[2]);
        self::assertSame(\App\Controller\Backend\Category::class, $actual[3]);
        self::assertSame(CategoryDetail::class, $actual[4]);
        self::assertSame(\App\Controller\Backend\ProductDetail::class, $actual[5]);
    }
}