<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\Redirect\Redirect;
use phpDocumentor\Reflection\Types\Parent_;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    protected RedirectMock $redirectMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->redirectMock = new RedirectMock();
    }

    public function testRedirect(): void
    {
        $this->redirectMock->redirect('test');
        $url = $this->redirectMock->url;
        self::assertSame('Location: test', $url);
    }
}

class RedirectMock extends Redirect
{
    public string $url;

    public function redirect(string $url): void
    {
        $this->url = ("Location: " . $url);
    }
}
