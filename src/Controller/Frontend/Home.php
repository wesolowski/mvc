<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\ProductRepository;
use App\Controller\ControllerInterface;

final class Home implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;
    private Redirect $redirect;
    private array $productList;

    public function __construct(ViewInterface $smartyController, ProductRepository $productRepository, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->productRepository = $productRepository;
        $this->redirect = $redirect;
        $this->productList = $this->productRepository->getList();
    }
    public function action(): void
    {
        $this->smartyController->addTlpParam('productList', $this->productList);
        $this->smartyController->addTemplate('home.tpl');

    }
}