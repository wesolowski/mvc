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
    private ?CategoryDataTransferObject $category;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $productRepository = $container->get(ProductRepository::class);
        $categoryRepository = $container->get(CategoryRepository::class);
        $this->redirect = $container->get(RedirectInterface::class);

        $categoryID = 0;
        if(isset($_GET['categoryID'])){
            $categoryID = (int)$_GET['categoryID'];
        }
        $this->category = $categoryRepository->getByID($categoryID);

        $this->productList = $productRepository->getList();
    }
    public function action(): void
    {
        if($this->category !== null) {
            $this->viewInterface->addTlpParam('category', $this->category);
            $this->viewInterface->addTlpParam('productList', $this->productList);
            $this->viewInterface->addTemplate('product.tpl');
        } else {
            $this->redirect->redirect('index.php');
        }
    }
}