<?php
declare(strict_types=1);
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";
//Database
$database = new \App\Model\Database();
$database->connect();
$connection = $database->getConnection();
//Basic's
$smartyView = new \App\Core\View\SmartyView(new Smarty());
$controllerProvider = new \App\Core\Provider\ControllerProvider();
$redirect = new \App\Core\Redirect\Redirect();
//Mapper's
$categoryMapper = new \App\Model\Mapper\CategoryMapper();
$productMapper = new \App\Model\Mapper\ProductMapper();
//Get URL Info's
$page = $_GET['page'] ?? 'Category';
$area = $_GET['area'] ?? 'Consumer';
if (isset($_GET['categoryID'])) {
    $categoryID = (int)$_GET['categoryID'];
} else {
    $categoryID = 0;
}
if (isset($_GET['productID'])) {
    $productID = (int)$_GET['productID'];
} else {
    $productID = 0;
}
//Repository's
$userRepository = new \App\Model\Repository\UserRepository($database);
$categoryRepository = new \App\Model\Repository\CategoryRepository($database);
$productRepository = new \App\Model\Repository\ProductRepository($categoryID, $database);
//Entity Manager's
$categoryEntityManager = new \App\Model\EntityManager\CategoryEntityManager($database);
$productEntityManager = new \App\Model\EntityManager\ProductEntityManager($database, $productRepository);
//Admin Area
$adminLogin = new \App\Core\AdminLogin($userRepository);
//Select Area
$controllerType = $controllerProvider->getFrontendList();
if ($area !== '') {
    if ($area === 'Consumer') {
        $controllerType = $controllerProvider->getFrontendList();
    } elseif ($area === 'Admin') {
        $controllerType = $controllerProvider->getBackendList();
    }
}
//Display
foreach ($controllerType as $class) {
    $page = new $class();
    if ($page instanceof \App\Controller\Backend\BackendControllerInterface) {
        if ($adminLogin->loggedIn() === false) {
            $redirect->redirect('index.php?page=a$Login');
        }
    }
    $page->action();
}

$smartyView->display();
$database->disconnect();