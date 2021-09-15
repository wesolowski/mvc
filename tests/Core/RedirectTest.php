<?php
declare(strict_types=1);

namespace AppTest\Core;
/*
use App\Core\Redirect;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    protected $redirect;

    protected function setUp(): void
    {
        $this->redirect = $this->getMockBuilder(Redirect::class)
            ->onlyMethods(['redirect'])
            ->getMock();
        $this->redirect
            ->expects($this->any())
            ->method('redirect')
            ->will(
                $this->returnCallback(function ($url) {
                    throw new \Exception($url);
                })
            );
    }

    public function testRedirect()
    {
        try{
            $this->redirect->redirect('index_old.php');
        }
        catch (\Exception $e){
            self::assertEquals($e->getMessage(), 'index_old.php');
        }
    }
}
*/