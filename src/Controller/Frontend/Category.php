<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Controller\ControllerInterface;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\Repository\ProductRepository;

final class Category implements ControllerInterface
{
    private ViewInterface $smartyController;
    private ProductRepository $productRepository;
    private Redirect $redirect;
    private array $productList;
    private array $category;

    public function __construct(ViewInterface $smartyController, array $repositoryType, Redirect $redirect)
    {
        $this->redirect = $redirect;
        $this->smartyController = $smartyController;
        $this->productRepository = $repositoryType['productRepository'];


        //TODO gehört in action
        if (preg_match('/^[\d]\$[A-Z][a-z]*$/', $_GET['category'])  && !empty($this->productRepository->getList())) {
            $this->category = explode('$', $_GET['category']);
        } else {
            $this->redirect->redirect('index.php');
        }
        $this->productList = $this->productRepository->getList();
    }
    public function action(): void
    {
        $category = [
            'id' => $this->category[0],
            'categoryname' => $this->category[1],
        ];
        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('productList', $this->productList);
        $this->smartyController->addTemplate('category.tpl');

    }
}