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
    private ProductRepository $productRepository;
    private CategoryDataTransferObject $category;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $redirect = $container->get(RedirectInterface::class);

        $categoryRepository = $container->get(CategoryRepository::class);

        $this->category = $categoryRepository->getByID((int)$_GET['categoryID']);
    }

    public function action(): void
    {
        $productID = (int)$_GET['productID'];
        $product = $this->productRepository->getByID($productID);

        $this->viewInterface->addTlpParam('category', $this->category);
        $this->viewInterface->addTlpParam('product', $product);

        $this->viewInterface->addTemplate('productDetail.tpl');
    }
}