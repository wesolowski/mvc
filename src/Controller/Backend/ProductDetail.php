<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Dto\ProductDataTransferObject;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Repository\ProductRepository;

class ProductDetail implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private ProductRepository $productRepository;
    private ProductEntityManager $productEntityManager;
    private CategoryProductEntityManager $categoryProductEntityManager;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->productEntityManager = $container->get(ProductEntityManager::class);
        $this->categoryProductEntityManager = $container->get(CategoryProductEntityManager::class);
        $this->redirect = $container->get(RedirectInterface::class);
    }

    public function action(): void
    {
        if(!isset($_GET['categoryId']) || $_GET['categoryId'] === ''){
            $this->redirect->redirect('index.php?area=Admin&page=Category');
        }
        $categoryId = (int)$_GET['categoryId'];

        if(!isset($_GET['productId']) || $_GET['productId'] === ''){
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);
        }
        $productId = (int)$_GET['productId'];
        $product = $this->productRepository->getByID($productId);

        if (!$product instanceof ProductDataTransferObject) {
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);
        }

        if (isset($_POST['updateProduct'])) {
            if (!isset($_POST["updateName"]) || $_POST["updateName"] === '') {
                $this->viewInterface->addTlpParam('error', 'Product name musst be given');
            } else {
                $price = $product->price;
                $description = $product->description;

                $product->name = $_POST["updateName"];

                if(isset($_POST['updatePrice'])) {
                    $price = (float)$_POST['updatePrice'];
                }

                $product->price = $price;
                $product->description = $_POST["updateDescription"] ?? $description;
                $this->productEntityManager->update($product);

                $this->redirect->redirect('index.php?area=Admin&page=ProductDetail&categoryId=' . $categoryId . '&productId=' . $product->id);
            }

        }

        if (isset($_POST['deleteProduct'])) {
            $this->productEntityManager->delete($product->id);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);
        }

        if (isset($_POST['removeProductFromCategory'])) {
            $this->categoryProductEntityManager->delete($categoryId, $product->id);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);
        }

        $this->viewInterface->addTlpParam('categoryId', $categoryId);
        $this->viewInterface->addTlpParam('product', $product);
        $this->viewInterface->addTemplate('backend/productDetail.tpl');
    }
}