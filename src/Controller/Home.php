<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\ProductRepository;

final class Home implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;
    private array $productList;

    public function __construct(ViewInterface $smartyController, ProductRepository $productRepository){
        $this->smartyController = $smartyController;
        $this->productRepository = $productRepository;
        $this->productList = $this->productRepository->getList();
    }
    public function action(): void
    {
        $this->smartyController->addTlpParam('productList', $this->productList);
        $this->smartyController->addTemplate('home.tpl');

    }
    public function getProductList(): array
    {
        return $this->productList;
    }
}