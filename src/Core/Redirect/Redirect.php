<?php
declare(strict_types=1);

namespace App\Core\Redirect;

class Redirect implements RedirectInterface
{
    /**
     * @infection-ignore-all
     */
    public function redirect(string $url): void
    {
        exit(header("Location: " . $url));
    }
}