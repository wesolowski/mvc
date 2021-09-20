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
        $categoryID = 0;
        $category = null;
        if (isset($_GET['categoryID'])) {
            $categoryID = (int)$_GET['categoryID'];

            $category = $this->categoryRepository->getByID($categoryID);

            if ($category !== null) {
                $productList = $this->productRepository->getList();
                $this->viewInterface->addTlpParam('category', $category);
                $this->viewInterface->addTlpParam('productList', $productList);
                $this->viewInterface->addTemplate('product.tpl');
            }
        }
        if ($categoryID === 0 || $category === null) {
            $this->redirect->redirect('index.php');
        }
    }
}