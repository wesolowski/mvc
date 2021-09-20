<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Dto\CategoryDataTransferObject;
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
        $categoryID = 0;
        $productID = 0;
        $category = null;
        $product = null;
        if(isset($_GET['categoryID'])) {
            $categoryID = (int)$_GET['categoryID'];
            $category = $this->categoryRepository->getById($categoryID);
            if ($category !== null) {
                if (isset($_GET['productID'])) {
                    $productID = (int)$_GET['productID'];
                    $product = $this->productRepository->getByID($productID);
                    if ($product !== null) {
                        $this->viewInterface->addTlpParam('category', $category);
                        $this->viewInterface->addTlpParam('product', $product);
                        $this->viewInterface->addTemplate('productDetail.tpl');
                    }
                }
            }
        }
        if($categoryID === 0 || $category === null){
            $this->redirect->redirect('index.php');
        } elseif($productID === 0 || $product === null){
            $this->redirect->redirect('');
        }

    }
}