<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\ProductRepository;
use App\Controller\ControllerInterface;

final class Detail implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;
    private Redirect $redirect;

    public function __construct(ViewInterface $smartyController, ProductRepository $productRepository, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->productRepository = $productRepository;
        $this->redirect = $redirect;
    }

    public function action(): void
    {
        $id = '';
        if(isset($_GET['id'])) {
            $id = (string)$_GET['id'];
        }

        if($this->productRepository->hasProduct($id) === false){
            $this->redirect->redirect("index.php");
            exit();
        }
        $product = $this->productRepository->getProduct($id);

        $category = $_GET['category'];
        if ($category === '' || !preg_match('/^[A-Z][a-z]*$/', $category)) {
            $this->redirect->redirect('index.php?page=c$Home');
        }

        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('product', $product);

        $this->smartyController->addTemplate('detail.tpl');
    }
}