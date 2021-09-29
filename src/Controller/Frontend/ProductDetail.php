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

class ProductDetail implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private RedirectInterface $redirect;
    private int $categoryId;
    private int $productId;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->redirect = $container->get(RedirectInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
    }

    private function isSet(string $type): void
    {
        if(isset($_GET[$type]) && $_GET[$type] !== ''){
            $this->$type = (int)$_GET[$type];
        } else {
            $this->$type = 0;
        }
    }

    public function action(): void
    {
        $this->isSet('categoryId');
        $categoryDTO = $this->categoryRepository->getById($this->categoryId);

        if (!$categoryDTO instanceof CategoryDataTransferObject) {
            $this->redirect->redirect('index.php');

            return;
        }

        $this->isSet('productId');
        $productDTO = $this->productRepository->getByID($this->productId);

        if (!$productDTO instanceof ProductDataTransferObject) {
            $this->redirect->redirect('index.php?area=Consumer&page=Product&categoryId=' . $this->categoryId);

            return;
        }

        $this->viewInterface->addTlpParam('categoryDTO', $categoryDTO);
        $this->viewInterface->addTlpParam('productDTO', $productDTO);
        $this->viewInterface->addTemplate('productDetail.tpl');
    }
}
