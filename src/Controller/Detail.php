<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\ProductRepository;

final class Detail implements ControllerInterface
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
        $id = (int)$_GET['id'];

        if($this->productRepository->hasProduct($id) === false){
            header("Location: /index.php");
            exit();
        }
        $product = $this->productRepository->getProduct($id);

        $this->smartyController->addTlpParam('product', $product);

        $this->smartyController->addTemplate('detail.tpl');
    }
}