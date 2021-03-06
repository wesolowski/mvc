<?php
declare(strict_types=1);

namespace App\Core\Provider;

use App\Controller\Frontend\Product;
use App\Controller\Frontend\ProductDetail;
use App\Controller\Frontend\Category;
use App\Controller\Backend\Login as BackendLogin;
use App\Controller\Backend\Home as BackendHome;
use App\Controller\Backend\Category as BackendCategory;
use App\Controller\Backend\CategoryDetail as BackendCategoryDetail;
use App\Controller\Backend\ProductDetail as BackendProductDetail;
use App\Controller\Backend\Logout as BackendLogout;

final class ControllerProvider
{
    public function getFrontendList(): array
    {
        return [
            Category::class,
            Product::class,
            ProductDetail::class,
        ];
    }
    public function getBackendList(): array
    {
        return [
            BackendLogin::class,
            BackendLogout::class,
            BackendHome::class,
            BackendCategory::class,
            BackendCategoryDetail::class,
            BackendProductDetail::class
        ];
    }
}