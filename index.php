<?php
declare(strict_types=1);
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

//Doctrine ORM
$doctrine = new \App\Model\Doctrine();
$entityManager = $doctrine->getEntityManager();

//Provider
$container = new \App\Core\Container();
$dependencyProvider = new \App\Core\Provider\DependencyProvider();
$dependencyProvider->provide($container, $entityManager);
$controllerProvider = new \App\Core\Provider\ControllerProvider();
//Other
$adminLogin = $container->get(\App\Core\AdminLogin::class);
$redirect = $container->get(\App\Core\Redirect\RedirectInterface::class);
$viewInterface = $container->get(\App\Core\View\ViewInterface::class);
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
//Controller Type
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
    if ($class === 'App\Controller\Frontend\\' . $page || $class === 'App\Controller\Backend\\' . $page) {
        $classPage = new $class($container);
        if ($classPage instanceof \App\Controller\Backend\BackendControllerInterface) {
            if ($adminLogin->loggedIn() === false) {
                $redirect->redirect('index.php?area=Admin&page=Login');
            }
        }
        $classPage->action();
    }
}

$viewInterface->display();