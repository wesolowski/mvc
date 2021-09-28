<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Dto\CategoryDataTransferObject;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

final class Product implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private ProductRepository $productRepository;
    private CategoryRepository $categoryRepository;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->redirect = $container->get(RedirectInterface::class);
    }

    public function action(): void
    {
        if (!isset($_GET['categoryId']) || $_GET['categoryId'] === '') {
            $this->redirect->redirect('index.php');
        }

        $categoryDTO = $this->categoryRepository->getByID((int)$_GET['categoryId']);

        if (!$categoryDTO instanceof CategoryDataTransferObject) {
            $this->redirect->redirect('index.php');
        }

        $productDTOList = $this->productRepository->getList();

        $this->viewInterface->addTlpParam('categoryDTO', $categoryDTO);
        $this->viewInterface->addTlpParam('productDTOList', $productDTOList);
        $this->viewInterface->addTemplate('product.tpl');
    }
}