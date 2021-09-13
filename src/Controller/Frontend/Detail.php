<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\Repository\ProductRepository;
use App\Controller\ControllerInterface;

final class Detail implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;
    private Redirect $redirect;
    private array $category;

    public function __construct(ViewInterface $smartyController, array $repositoryType, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->productRepository = $repositoryType['productRepository'];
        $this->redirect = $redirect;

        if ((isset($_GET['id']) && $_GET['id'] !== '') && preg_match('/^[\d]\$[A-Z][a-z]*$/', $_GET['category']) && !empty($this->productRepository->getList())) {
            $this->category = explode('$', $_GET['category']);
        } else {
            $this->redirect->redirect('index.php?page=p$Category&category=3$Clothing');
        }

    }

    public function action(): void
    {
        $category = [
            'id' => $this->category[0],
            'categoryname' => $this->category[1],
        ];

        $id = (int)$_GET['id'];
        $product = $this->productRepository->getByID($id);

        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('product', $product);

        $this->smartyController->addTemplate('detail.tpl');
    }
}