<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class EditCategory implements ControllerInterface
{
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private ViewInterface $smartyController;
    private CategoryEntityManager $categoryEntityManager;

    public function __construct(ViewInterface $smartyController, array $repositoryEntityType, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->categoryRepository = $repositoryEntityType['categoryRepository'];
        $this->productRepository = $repositoryEntityType['productRepository'];
        $this->categoryEntityManager = $repositoryEntityType['categoryEntityManager'];

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
        $newCategoryName = $_POST['newCategoryName'] ?? $category->categoryname;

        if(isset($_POST['updateCategory'])){
            $this->categoryEntityManager->update(['categoryname' => $newCategoryName, 'id' => $category->id]);
            $this->redirect->redirect('index.php?page=ap$EditCategory&category=3$' . $newCategoryName);
            $_POST = [];
        } elseif (isset($_POST['deleteCategory'])){
            $this->categoryEntityManager->getProductRepository($this->productRepository);
            $this->categoryEntityManager->delete($category->id);
            $this->redirect->redirect('index.php?page=ap$Category');
            $_POST = [];
        }

        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('productList', $this->productRepository->getList());
        $this->smartyController->addTlpParam('newCategoryName', $newCategoryName);
        $this->smartyController->addTemplate('backend/editCategory.tpl');
    }
}