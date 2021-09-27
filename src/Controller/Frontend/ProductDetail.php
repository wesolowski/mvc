<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Dto\ProductDataTransferObject;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use App\Controller\ControllerInterface;

final class ProductDetail implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->redirect = $container->get(RedirectInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
    }

    public function action(): void
    {
        if (!isset($_GET['categoryId']) && $_GET['categoryId'] === '') {
            $this->redirect->redirect('index.php');
        }

        $categoryId = (int)$_GET['categoryId'];
        $categoryDTO = $this->categoryRepository->getById($categoryId);

        if (!$categoryDTO instanceof CategoryDataTransferObject) {
            $this->redirect->redirect('index.php');
        }

        if (!isset($_GET['productId']) && $_GET['productId'] === '') {
            $this->redirect->redirect('index.php?area=Consumer&page=Product&categoryId=' . $categoryId);
        }

        $productId = (int)$_GET['productId'];
        $productDTO = $this->productRepository->getByID($productId);

        if (!$productDTO instanceof ProductDataTransferObject) {
            $this->redirect->redirect('index.php?area=Consumer&page=Product&categoryId=' . $categoryId);
        }

        $this->viewInterface->addTlpParam('categoryDTO', $categoryDTO);
        $this->viewInterface->addTlpParam('productDTO', $productDTO);
        $this->viewInterface->addTemplate('productDetail.tpl');
    }
}