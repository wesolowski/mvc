<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\ProductRepository;

final class Category implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;

    public function __construct(ViewInterface $smartyController, ProductRepository $productRepository)
    {
        $this->smartyController = $smartyController;
        $this->productRepository = $productRepository;
    }



    public function action(): void
    {
        $this->smartyController->addTlpParam('content', 'Category page!');
        $this->smartyController->addTemplate('category.tpl');
    }
}