<?php
declare(strict_types=1);

namespace App\Controller;

final class Category
{
    private ViewInterface $smartyController;

    public function __construct(ViewInterface $smartyController)
    {
        $this->smartyController = $smartyController;
    }



    public function action(): void
    {
        $this->smartyController->assign('title', 'Categories');
        $this->smartyController->assign('content', 'Category page!');
        $this->smartyController->displayPage('index.tpl');
    }
}