<?php
declare(strict_types=1);

namespace AppTest\Controller;

use App\Core\Redirect\Redirect;

class RedirectMock extends Redirect
{
    public ?string $url = null;

    public function redirect(string $url): void
    {
        $this->url = $url;
    }
}
