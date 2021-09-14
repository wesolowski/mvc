<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Repository\ProductRepository;

final class Product implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private array $productList;
    private array $category;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $productRepository = $container->get(ProductRepository::class);
        $redirect = $container->get(RedirectInterface::class);

        //TODO gehört in action
        if (preg_match('/^[\d]\$[A-Z][a-z]*$/', $_GET['category'])  && !empty($productRepository->getList())) {
            $this->category = explode('$', $_GET['category']);
        } else {
            $redirect->redirect('index_old.php');
        }
        $this->productList = $productRepository->getList();
    }
    public function action(): void
    {
        $category = [
            'id' => $this->category[0],
            'categoryname' => $this->category[1],
        ];
        $this->viewInterface->addTlpParam('category', $category);
        $this->viewInterface->addTlpParam('productList', $this->productList);
        $this->viewInterface->addTemplate('product.tpl');

    }
}