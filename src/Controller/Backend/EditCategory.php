<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\ProductEntityManager;
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
                $this->categoryEntityManager->update(['categoryname' => $editCategoryName, 'id' => $category->id]);
                $this->redirect->redirect('index.php?page=ap$EditCategory&category='.$category->id . '$' . $editCategoryName);
                $_POST = [];
            }
        } elseif (isset($_POST['deleteCategory'])){
            $this->categoryEntityManager->delete(['id' => $category->id, 'productRepositoryList' => $this->productRepository->getList()]);
            $this->redirect->redirect('index.php?page=ap$Category');
            $_POST = [];
        } elseif (isset($_POST['createProduct'])) {
            $newProductName = $_POST['newProductName'] ?? '';
            $newProductDescription = $_POST['newProductDescription'] ?? null;
            if($newProductName === ''){
                $this->smartyController->addTlpParam('error', ['product' => 'Product Name musst be given']);
            }
            else {
                $this->productEntityManager->insert(['categoryID' => $category->id, 'productname' => $newProductName, 'description' => $newProductDescription]);
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