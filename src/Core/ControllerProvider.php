<?php
declare(strict_types=1);

namespace App\Core;

use App\Controller\Category;
use App\Controller\Detail;
use App\Controller\Home;

final class ControllerProvider
{
    public function getList(): array
    {
        return [
            Category::class,
            Detail::class,
            Home::class
        ];
    }
}