<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class EditCategory implements ControllerInterface
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private ViewInterface $smartyController;
    private CategoryEntityManager $categoryEntityManager;
    private ProductEntityManager $productEntityManager;
    private Redirect $redirect;
    private CategoryMapper $categoryMapper;
    private ProductMapper $productMapper;

    public function __construct(ViewInterface $smartyController, array $repositoryEntityType, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->categoryRepository = $repositoryEntityType['categoryRepository'];
        $this->productRepository = $repositoryEntityType['productRepository'];
        $this->categoryEntityManager = $repositoryEntityType['categoryEntityManager'];
        $this->productEntityManager = $repositoryEntityType['productEntityManager'];


        //in seperate methode
        $this->redirect = $redirect;
        $adminLogin = new AdminLogin($repositoryEntityType['userRepository']);
        $this->categoryMapper = new CategoryMapper();
        $this->productMapper = new ProductMapper();

        if($adminLogin->loggedIn() === false){
            $redirect->redirect('index.php?page=a$Login');
        }
    }

    public function action(): void
    {
        $categoryname = explode('$', $_GET['category'])[1];
        $category = $this->categoryRepository->getByName($categoryname);
        $editCategoryName = $_POST['editCategoryName'] ?? $category->categoryname;

        if(isset($_POST['updateCategory'])){
            if($editCategoryName === ''){
                $this->smartyController->addTlpParam('error', ['category' => 'Category Name musst be given']);
            } else {
                $mappedCategory = $this->categoryMapper->map(['CategoryName' => $editCategoryName, 'CategoryID' => $category->id]);
                $this->categoryEntityManager->update();
                $this->redirect->redirect('index.php?page=ap$EditCategory&category='.$category->id . '$' . $editCategoryName);
                $_POST = [];
            }
        } elseif (isset($_POST['deleteCategory'])){
            $this->categoryEntityManager->delete($category->id);
            $this->redirect->redirect('index.php?page=ap$Category');
            $_POST = [];
        } elseif (isset($_POST['createProduct'])) {
            if($_POST['newProductName'] === ''){
                $this->smartyController->addTlpParam('error', ['product' => 'Product Name musst be given']);
            }
            else {
                $mappedProduct = $this->productMapper->map(['CategoryID' => $category->id, 'ProductName' => $_POST['newProductName'], 'ProductDescription' => $_POST['newProductDescription']]);
                $this->productEntityManager->insert($mappedProduct);
                $_POST = [];
            }
        } elseif (isset($_POST['addProduct'])) {

        }


        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('productList', $this->productRepository->getList());
        $this->smartyController->addTlpParam('productListExcludeCategory', $this->productRepository->getListExcludeCategory());
        $this->smartyController->addTlpParam('editCategoryName', $editCategoryName);
        $this->smartyController->addTemplate('backend/editCategory.tpl');
    }
}