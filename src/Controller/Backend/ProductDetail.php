<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\ProductRepository;

class ProductDetail implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private ProductRepository $productRepository;
    private ProductEntityManager $productEntityManager;
    private RedirectInterface $redirect;
    private ProductMapper $productMapper;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->productEntityManager = $container->get(ProductEntityManager::class);
        $this->redirect = $container->get(RedirectInterface::class);
        $this->productMapper = $container->get(ProductMapper::class);
    }

    public function action(): void
    {
        $editProduct = [];
        $categoryID = $_GET['categoryID'] ?? null;
        $product = $this->productRepository->getByID((int)$_GET['productID']);
        if ($categoryID === null) {
            $this->redirect->redirect('index.php?area=Admin&page=Category');
        }
        if ($product === null) {
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryID);
        }
        if (isset($_POST['updateProduct'])) {
            $productname = $_POST["editProductName"] ?? '';
            $description = $_POST["editProductDescription"] ?? null;
            if($productname === ''){
                $editProduct['name'] = $productname;
                $editProduct['description'] = $description;
                $this->viewInterface->addTlpParam('error', 'Product name musst be given');
            } else {
                $mappedProduct = $this->productMapper->map(['ProductID' => $product->id, 'ProductName' => $productname, 'ProductDescription' => $description]);
                $this->productEntityManager->update($mappedProduct);
                $this->redirect->redirect('index.php?area=Admin&page=ProductDetail&categoryID=' . $categoryID . '&productID=' . $product->id);
                $_POST = [];
            }
        } elseif (isset($_POST['deleteProduct'])) {
            $this->productEntityManager->delete($product->id);
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryID);
            $_POST = [];
        } else {
            $editProduct['name'] = $product->productname;
            $editProduct['description'] = $product->description;
        }

        $this->viewInterface->addTlpParam('categoryID', $categoryID);
        $this->viewInterface->addTlpParam('product', $product);
        $this->viewInterface->addTlpParam('editProduct', $editProduct);
        $this->viewInterface->addTemplate('backend/productDetail.tpl');
    }
}