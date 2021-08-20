<?php
declare(strict_types=1);

namespace App\Controller;

interface ViewInterface
{
    public function assign(string $key, $value): void;

    public function displayPage(string $page): void;
}