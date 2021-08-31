<?php
declare(strict_types=1);

namespace App\Core;

use App\Controller\Frontend\Detail;
use App\Controller\Home;
use App\Controller\Login;

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
}