<?php
declare(strict_types=1);

namespace App\Controller;

final class Home
{
    private ViewInterface $smartyController;

    public function __construct(ViewInterface $smartyController){
        $this->smartyController = $smartyController;
    }

    public function action(): void
    {
        $this->smartyController->assign('title', 'Home');
        $this->smartyController->assign('content', 'Home page!');
        $this->smartyController->displayPage('index.tpl');
    }
}