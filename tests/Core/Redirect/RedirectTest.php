<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\Redirect;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testRedirect()
    {
        $redirect = new Redirect\Redirect();
        $redirect->redirect('index.php');

        $this->assertContains(
            'Location: index.php', xdebug_get_headers()
        );
    }
}
