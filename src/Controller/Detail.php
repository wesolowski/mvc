<?php
declare(strict_types=1);

namespace App\Controller;

final class Detail
{
    private ViewInterface $smartyController;
    public function __construct(ViewInterface $smartyController)
    {
        $this->smartyController = $smartyController;
    }

    public function action(): void
    {
        $this->smartyController->assign('title', 'Details');
        $this->smartyController->assign('content', 'Detail page!');
        $this->smartyController->displayPage('index.tpl');
    }
}