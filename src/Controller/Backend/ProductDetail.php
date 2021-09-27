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
        $categoryID = $_GET['categoryID'] ?? '';

        if(!isset($_GET['categoryID']) || $_GET['categoryID'] === ''){
            $this->redirect->redirect('index.php?area=Admin&page=Category');
        }
        $productID = (int)$_GET['productID'];
        $product = $this->productRepository->getByID($productID);

        if (!$product instanceof ProductDataTransferObject) {
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryID);
        }

        if (isset($_POST['updateProduct'])) {
            if (!isset($_POST["editProductName"]) || $_POST["editProductName"] === '') {
                $this->viewInterface->addTlpParam('error', 'Product name musst be given');
            } else {
                $product->productname = $_POST["editProductName"];
                $product->description = $_POST["editProductDescription"] ?? 'NULL';
                $this->productEntityManager->update($product);

                $this->redirect->redirect('index.php?area=Admin&page=ProductDetail&categoryID=' . $categoryID . '&productID=' . $product->id);
            }

        }

        if (isset($_POST['deleteProduct'])) {
            $this->productEntityManager->delete($product->id);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryID);
        }

        if (isset($_POST['removeProductFromCategory'])) {
            $this->categoryProductEntityManager->delete((int)$categoryID, $product->id);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryID);
        }

        $this->viewInterface->addTlpParam('categoryID', $categoryID);
        $this->viewInterface->addTlpParam('product', $product);
        $this->viewInterface->addTemplate('backend/productDetail.tpl');
    }
}