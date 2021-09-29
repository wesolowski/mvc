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

    private function updateProduct(ProductDataTransferObject $productDTO, int $categoryId): void
    {
        if (!isset($_POST["updateName"]) || $_POST["updateName"] === '') {
            $this->viewInterface->addTlpParam('error', 'Product name musst be given');
        } else {
            $price = $productDTO->price;
            $description = $productDTO->description;

            $productDTO->name = $_POST["updateName"];

            if(isset($_POST['updatePrice'])) {
                $price = (float)$_POST['updatePrice'];
            }

            $productDTO->price = $price;
            $productDTO->description = $_POST["updateDescription"] ?? $description;
            $this->productEntityManager->update($productDTO);

            $this->redirect->redirect('index.php?area=Admin&page=ProductDetail&categoryId=' . $categoryId . '&productId=' . $productDTO->id);
        }
    }

    public function action(): void
    {
        if(!isset($_GET['categoryId']) || $_GET['categoryId'] === ''){
            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }
        $categoryId = (int)$_GET['categoryId'];

        if(!isset($_GET['productId']) || $_GET['productId'] === ''){
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);

            return;
        }
        $productId = (int)$_GET['productId'];
        $productDTO = $this->productRepository->getByID($productId);

        if (!$productDTO instanceof ProductDataTransferObject) {
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);

            return;
        }

        if (isset($_POST['updateProduct'])) {
            $this->updateProduct($productDTO, $categoryId);
            return;
        }

        if (isset($_POST['deleteProduct'])) {
            $this->productEntityManager->delete($productDTO->id);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);

            return;
        }

        if (isset($_POST['removeProductFromCategory'])) {
            $this->categoryProductEntityManager->delete($categoryId, $productDTO->id);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryId);

            return;
        }

        $this->viewInterface->addTlpParam('categoryId', $categoryId);
        $this->viewInterface->addTlpParam('product', $productDTO);
        $this->viewInterface->addTemplate('backend/productDetail.tpl');
    }
}
