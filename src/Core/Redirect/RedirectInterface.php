<?php
declare(strict_types=1);

namespace App\Core\Redirect;

interface RedirectInterface
{
    public function redirect(string $url): void;
}