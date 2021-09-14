<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
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
    private CategoryMapper $categoryMapper;
    private ProductMapper $productMapper;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->categoryEntityManager = $container->get(CategoryEntityManager::class);
        $this->productEntityManager = $container->get(ProductEntityManager::class);
        $this->categoryMapper = $container->get(CategoryMapper::class);
        $this->productMapper = $container->get(ProductMapper::class);
        $this->redirect = $container->get(RedirectInterface::class);
    }

    public function action(): void
    {
        $category = $this->categoryRepository->getById((int)$_GET['categoryID']);
        $editCategoryName = $_POST['editCategoryName'] ?? $category->categoryname;

        if(isset($_POST['updateCategory'])){
            if($editCategoryName === ''){
                $this->viewInterface->addTlpParam('error', ['category' => 'Product Name musst be given']);
            } else {
                $mappedCategory = $this->categoryMapper->map(['CategoryName' => $editCategoryName, 'CategoryID' => $category->id]);
                $this->categoryEntityManager->update($mappedCategory);
                $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryID='.$category->id);
                $_POST = [];
            }
        } elseif (isset($_POST['deleteCategory'])){
            $this->categoryEntityManager->delete($category->id);
            $this->redirect->redirect('index.php?area=Admin&page=Category');
            $_POST = [];
        } elseif (isset($_POST['createProduct'])) {
            if($_POST['newProductName'] === ''){
                $this->viewInterface->addTlpParam('error', ['product' => 'Product Name musst be given']);
            }
            else {
                $mappedProduct = $this->productMapper->map(['CategoryID' => $category->id, 'ProductName' => $_POST['newProductName'], 'ProductDescription' => $_POST['newProductDescription']]);
                $this->productEntityManager->insert($mappedProduct);
                $_POST = [];
            }
        } elseif (isset($_POST['addProduct'])) {
            $productID = (int)$_POST['selectProduct'];

        }

        $this->viewInterface->addTlpParam('category', $category);
        $this->viewInterface->addTlpParam('productList', $this->productRepository->getList());
        $this->viewInterface->addTlpParam('productListExcludeCategory', $this->productRepository->getListExcludeCategory());
        $this->viewInterface->addTlpParam('editCategoryName', $editCategoryName);
        $this->viewInterface->addTemplate('backend/categoryDetail.tpl');
    }
}