<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\Redirect\Redirect;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    protected Redirect $redirect;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redirect = $this->getMockBuilder(Redirect::class)
            ->onlyMethods(['redirect'])
            ->getMock();
        $this->redirect
            ->expects(self::once())
            ->method('redirect');
    }

    public function testRedirect(): void
    {
        $this->redirect->redirect('index.php');
    }
}
