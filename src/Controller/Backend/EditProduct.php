<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class EditProduct implements ControllerInterface
{
    private ViewInterface $smartyController;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private ProductEntityManager $productEntityManager;
    private Redirect $redirect;

    public function __construct(ViewInterface $smartyController, array $repositoryEntityType, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->categoryRepository = $repositoryEntityType['categoryRepository'];
        $this->productRepository = $repositoryEntityType['productRepository'];
        $this->productEntityManager = $repositoryEntityType['productEntityManager'];
        $this->redirect = $redirect;

        $adminLogin = new AdminLogin($repositoryEntityType['userRepository']);

        if ($adminLogin->loggedIn() === false) {
            $redirect->redirect('index.php?page=a$Login');
        }
    }

    public function action(): void
    {
        $editProduct = [];
        $category = $_GET['category'] ?? null;
        $product = $this->productRepository->getByID($_GET['id']);
        if ($category === null) {
            $this->redirect->redirect('index.php?page=ac$Category');
        }
        if ($product === null) {
            $this->redirect->redirect('index.php?page=ap$EditCategory&category=' . $category);
        }
        if (isset($_POST['updateProduct'])) {
            $productname = $_POST["editProductName"];
            $description = $_POST["editProductDescription"];
            $this->productEntityManager->update(['id' => $product->id, 'productname' => $productname, 'description' => $description]);
            $this->redirect->redirect('index.php?page=ap$EditProduct&category=' . $category . '&id=' . $product->id);
            $_POST = [];
        } elseif (isset($_POST['deleteProduct'])) {
            $this->productEntityManager->delete(['id' => $product->id]);
            $this->redirect->redirect('index.php?page=ap$EditCategory&category=' . $category);
            $_POST = [];
        } else {
            $editProduct['name'] = $product->productname;
            $editProduct['description'] = $product->description;
        }

        $this->smartyController->addTlpParam('category', $category);
        $this->smartyController->addTlpParam('product', $product);
        $this->smartyController->addTlpParam('editProduct', $editProduct);
        $this->smartyController->addTemplate('backend/editProduct.tpl');
    }
}