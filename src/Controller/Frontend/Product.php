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
    private array $productList;
    private CategoryDataTransferObject $category;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $productRepository = $container->get(ProductRepository::class);
        $categoryRepository = $container->get(CategoryRepository::class);

        $this->category = $categoryRepository->getByID((int)$_GET['categoryID']);
        $this->productList = $productRepository->getList();
    }
    public function action(): void
    {
        $this->viewInterface->addTlpParam('category', $this->category);
        $this->viewInterface->addTlpParam('productList', $this->productList);
        $this->viewInterface->addTemplate('product.tpl');

    }
}