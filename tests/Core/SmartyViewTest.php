<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\SmartyView;
use PHPUnit\Framework\TestCase;

class SmartyViewTest extends TestCase
{
    public function testAddTlpParam(): void
    {
        $SmartyView = new SmartyView(new \Smarty());

        $SmartyView->addTlpParam('Test1', '1');
        $SmartyView->addTlpParam('Test2', '2');

        self::assertCount(2, $SmartyView->getParams());

        self::assertSame('1', $SmartyView->getParams()['Test1']);
        self::assertSame('2', $SmartyView->getParams()['Test2']);
    }
    public function testAddTemplates(): void
    {
        $SmartyView = new SmartyView(new \Smarty());

        $SmartyView->addTemplate('template.tpl');

        self::assertSame('template.tpl', $SmartyView->getTemplate());
    }
}