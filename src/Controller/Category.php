<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;

final class Category implements ControllerInterface
{
    private ViewInterface $smartyController;

    public function __construct(ViewInterface $smartyController)
    {
        $this->smartyController = $smartyController;
    }



    public function action(ProductRepository $pr): void
    {
        $this->smartyController->addTlpParam('content', 'Category page!');
        $this->smartyController->addTemplate('category.tpl');
    }
}