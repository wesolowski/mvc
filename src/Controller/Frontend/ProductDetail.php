<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Repository\ProductRepository;
use App\Controller\ControllerInterface;

final class ProductDetail implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private ProductRepository $productRepository;
    private array $category;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $redirect = $container->get(RedirectInterface::class);

        if ((isset($_GET['id']) && $_GET['id'] !== '') && preg_match('/^[\d]\$[A-Z][a-z]*$/', $_GET['category']) && !empty($this->productRepository->getList())) {
            $this->category = explode('$', $_GET['category']);
        } else {
            $redirect->redirect('index.php?page=p$Product&category=3$Clothing');
        }

    }

    public function action(): void
    {
        $category = [
            'id' => $this->category[0],
            'categoryname' => $this->category[1],
        ];

        $id = (int)$_GET['id'];
        $product = $this->productRepository->getByID($id);

        $this->viewInterface->addTlpParam('category', $category);
        $this->viewInterface->addTlpParam('product', $product);

        $this->viewInterface->addTemplate('detail.tpl');
    }
}