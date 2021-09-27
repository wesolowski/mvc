<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Dto\CategoryDataTransferObject;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class CategoryDetail implements BackendControllerInterface
{
    private ViewInterface $viewInterface;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private CategoryEntityManager $categoryEntityManager;
    private ProductEntityManager $productEntityManager;
    private CategoryProductEntityManager $categoryProductEntityManager;
    private ProductMapper $productMapper;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->categoryEntityManager = $container->get(CategoryEntityManager::class);
        $this->productEntityManager = $container->get(ProductEntityManager::class);
        $this->categoryProductEntityManager = $container->get(CategoryProductEntityManager::class);
        $this->productMapper = $container->get(ProductMapper::class);
        $this->redirect = $container->get(RedirectInterface::class);
    }

    public function action(): void
    {
        $errors = [];

        if (!isset($_GET['categoryID']) || $_GET['categoryID'] === '') {
            $this->redirect->redirect('index.php?area=Admin&page=Category');
        }
        $categoryDTO = $this->categoryRepository->getById((int)$_GET['categoryID']);
        $editCategoryName = $_POST['editCategoryName'] ?? $categoryDTO->categoryname;

        if (!$categoryDTO instanceof CategoryDataTransferObject) {
            $this->redirect->redirect('index.php?area=Admin&page=Category');
        }

        if (isset($_POST['updateCategory'])) {
            if ($editCategoryName === '') {
                $errors['categoryDTO'] = 'Product Name musst be given';
            } else {
                $categoryDTO->categoryname = trim($editCategoryName);
                $this->categoryEntityManager->update($categoryDTO);
                $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryDTO->id);
            }
        }

        if (isset($_POST['deleteCategory'])) {
            $this->categoryEntityManager->delete($categoryDTO->id);

            $this->redirect->redirect('index.php?area=Admin&page=Category');
        }

        if (isset($_POST['createProduct'])) {
            $newProductName = $_POST['newProductName'] ?? '';
            $newProductDescription = $_POST['newProductDescription'] ?? 'NULL';

            if ($newProductName === '') {
                $errors['product'] = 'Product Name musst be given';
            } else {
                $mappedProduct = $this->productMapper->map(['CategoryID' => $categoryDTO->id, 'ProductName' => trim($newProductName), 'ProductDescription' => trim($newProductDescription)]);
                $this->productEntityManager->insert($mappedProduct);
            }
        }

        if (isset($_POST['addProduct'], $_POST['selectProduct'])) {
            $selectProduct = (int)$_POST['selectProduct'];
            $this->categoryProductEntityManager->insert($categoryDTO->id, $selectProduct);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID=' . $categoryDTO->id);
        }

        if(!empty($errors)){
            $this->viewInterface->addTlpParam('error', $errors);
        }

        $this->viewInterface->addTlpParam('category', $categoryDTO);
        $this->viewInterface->addTlpParam('productList', $this->productRepository->getList());
        $this->viewInterface->addTlpParam('productListExcludeCategory', $this->productRepository->getListExcludeCategory());
        $this->viewInterface->addTlpParam('editCategoryName', $editCategoryName);
        $this->viewInterface->addTemplate('backend/categoryDetail.tpl');
    }
}