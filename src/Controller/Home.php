<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\ProductRepository;

final class Home implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;

    public function __construct(ViewInterface $smartyController, ProductRepository $productRepository){
        $this->smartyController = $smartyController;
        $this->productRepository = $productRepository;
    }
    public function action(): void
    {
        //Name als link ausgeben der dann auf details weiterleitet
        $productList = $this->productRepository->getList();
        //html in smarty nur
        foreach ($productList as $product){
            //$this->smartyController->addTlpParam('productname', $product['productname']);
            $this->smartyController->addTlpParam('productList', $productList);
        }
        $this->smartyController->addTemplate('home.tpl');

    }
}