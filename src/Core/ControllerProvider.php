<?php
declare(strict_types=1);

namespace App\Core;

use App\Controller\Frontend\Detail;
use App\Controller\Frontend\Home;
use App\Controller\Backend\Login;

final class ControllerProvider
{
    public function getFrontendList(): array
    {
        return [
            Detail::class,
            Home::class,
        ];
    }
    public function getBackendList(): array
    {
        return [
            Login::class
        ];
    }
}