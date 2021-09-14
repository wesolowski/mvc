<?php
declare(strict_types=1);

namespace App\Core\View;

interface ViewInterface
{
    public function addTlpParam(string $key, $value): void;

    public function addTemplate(string $template): void;

    public function display(): void;
}