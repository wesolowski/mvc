<?php
declare(strict_types=1);

namespace AppTest\Core;

use App\Core\View\SmartyView;
use PHPUnit\Framework\TestCase;
use function Symfony\Component\String\s;

class SmartyViewTest extends TestCase
{
    protected SmartyView $smartyView;
    protected \Smarty $smarty;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smarty = new \Smarty();
        $this->smartyView = new SmartyView($this->smarty);
    }

    public function testSettings(): void
    {
        self::assertSame('/home/philipphermes/phpProjects/MVC/template/', $this->smarty->getTemplateDir()[0]);
        self::assertSame('/home/philipphermes/phpProjects/MVC/smarty/templates_c/', $this->smarty->getCompileDir());
        self::assertSame('/home/philipphermes/phpProjects/MVC/smarty/cache/', $this->smarty->getCacheDir());
        self::assertSame('/home/philipphermes/phpProjects/MVC/smarty/configs/', $this->smarty->getConfigDir()[0]);
    }

    public function testAddTlpParam(): void
    {
        $this->smartyView->addTlpParam('Test1', '1');
        $this->smartyView->addTlpParam('Test2', '2');

        self::assertCount(2, $this->smartyView->getParams());

        self::assertSame('1', $this->smartyView->getParams()['Test1']);
        self::assertSame('2', $this->smartyView->getParams()['Test2']);
    }

    public function testAddTemplates(): void
    {
        $this->smartyView->addTemplate('template.tpl');

        self::assertSame('template.tpl', $this->smartyView->getTemplate());
    }
}