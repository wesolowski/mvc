<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Controller\ControllerInterface;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\ProductRepository;

final class Category implements ControllerInterface
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
        $category = $_GET['category'];
        if ($category === '' || !preg_match('/^[A-Z][a-z]*$/', $category)) {
            $this->redirect->redirect('index.php');
        }
        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('productList', $this->productList);
        $this->smartyController->addTemplate('category.tpl');

    }
}