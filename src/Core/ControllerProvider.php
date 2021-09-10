<?php
declare(strict_types=1);

namespace App\Core;

use App\Controller\Frontend\Category;
use App\Controller\Frontend\Detail;
use App\Controller\Frontend\Home;
use App\Controller\Backend\Login as BackendLogin;
use App\Controller\Backend\Home as BackendHome;
use App\Controller\Backend\Category as BackendCategory;
use App\Controller\Backend\EditCategory;
use App\Controller\Backend\EditProduct;

final class ControllerProvider
{
    public function getFrontendList(): array
    {
        return [
            Category::class,
            Detail::class,
            Home::class,
        ];
    }
    public function getBackendList(): array
    {
        return [
            EditCategory::class,
            BackendCategory::class,
            BackendLogin::class,
            BackendHome::class,
            EditProduct::class
        ];
    }
}