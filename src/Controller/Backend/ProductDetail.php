<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Repository\ProductRepository;

class ProductDetail implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private ProductRepository $productRepository;
    private ProductEntityManager $productEntityManager;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->productEntityManager = $container->get(ProductEntityManager::class);
        $this->redirect = $container->get(RedirectInterface::class);
    }

    public function action(): void
    {
        $editProduct = [];
        $category = $_GET['category'] ?? null;
        $product = $this->productRepository->getByID((int)$_GET['id']);
        if ($category === null) {
            $this->redirect->redirect('index.php?page=ac$Product');
        }
        if ($product === null) {
            $this->redirect->redirect('index.php?page=ap$CategoryDetail&category=' . $category);
        }
        if (isset($_POST['updateProduct'])) {
            $productname = $_POST["editProductName"] ?? '';
            $description = $_POST["editProductDescription"] ?? null;
            if($productname === ''){
                $editProduct['name'] = $productname;
                $editProduct['description'] = $description;
                $this->viewInterface->addTlpParam('error', 'Product name musst be given');
            } else {
                $this->productEntityManager->update(['id' => $product->id, 'productname' => $productname, 'description' => $description]);
                $this->redirect->redirect('index.php?page=ap$ProductDetail&category=' . $category . '&id=' . $product->id);
                $_POST = [];
            }
        } elseif (isset($_POST['deleteProduct'])) {
            $this->productEntityManager->delete($product->id);
            $this->redirect->redirect('index.php?page=ap$CategoryDetail&category=' . $category);
            $_POST = [];
        } else {
            $editProduct['name'] = $product->productname;
            $editProduct['description'] = $product->description;
        }

        $this->viewInterface->addTlpParam('category', $category);
        $this->viewInterface->addTlpParam('product', $product);
        $this->viewInterface->addTlpParam('editProduct', $editProduct);
        $this->viewInterface->addTemplate('backend/editProduct.tpl');
    }
}