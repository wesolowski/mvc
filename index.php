<?php
declare(strict_types=1);
session_start();

use App\Controller\ControllerInterface;
use App\Core\SmartyView;
use App\Core\ControllerProvider;
use App\Core\Redirect;
use App\Model\Database;
use App\Model\Repository\ProductRepository;
use App\Model\Repository\UserRepository;
use App\Model\Repository\CategoryRepository;
use \App\Model\EntityManager\CategoryEntityManager;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

$db = new Database();
$db->connect();

$smarty = new SmartyView(new Smarty());
$provider = new ControllerProvider();
$repositoryType = [];
$redirect = new Redirect();

$providerType = null;
$searchNamespace = 'App\Controller\\';

$search = $_GET['page'] ?? 'c$Home';
$searchExplode = explode('$', $search);

if (count($searchExplode) === 2) {
    if ($searchExplode[0] === 'c') {
        $searchNamespace .= 'Frontend\\';
        $providerType = $provider->getFrontendList();
        $repositoryType[0] = new CategoryRepository($db);
    } elseif ($searchExplode[0] === 'p') {
        $searchNamespace .= 'Frontend\\';
        $providerType = $provider->getFrontendList();
        $category = $_GET['category'] ?? '';
        $repositoryType[0] = new ProductRepository($category, $db);
    } elseif ($searchExplode[0] === 'a') {
        $searchNamespace .= 'Backend\\';
        $providerType = $provider->getBackendList();
        $repositoryType[0] = new UserRepository($db);
    } elseif ($searchExplode[0] === 'ac') {
        $searchNamespace .= 'Backend\\';
        $providerType = $provider->getBackendList();
        $repositoryType[0] = new CategoryRepository($db);
        $repositoryType[1] = new UserRepository($db);
    }  elseif ($searchExplode[0] === 'ap') {
        $searchNamespace .= 'Backend\\';
        $providerType = $provider->getBackendList();
        $category = $_GET['category'] ?? '';
        $repositoryType = [];
        $repositoryType['categoryRepository'] = new CategoryRepository($db);
        $repositoryType['productRepository'] = new ProductRepository($category, $db);
        $repositoryType['userRepository'] = new UserRepository($db);
        $repositoryType['categoryEntityManager'] = new CategoryEntityManager($db, $repositoryType['categoryRepository']);
    }
    if (isset($repositoryType)) { //TODO fix page Call
        foreach ($providerType as $className) {
            if ($searchNamespace . $searchExplode[1] === $className) {
                $page = new $className($smarty, $repositoryType, $redirect);
                if (!$page instanceof ControllerInterface) {
                    throw new RuntimeException('Class ' . $className . ' is not instance of ' . ControllerInterface::class);
                }
                $page->action();
            } else {
                $smarty->addTlpParam('errormessage', 'error: Page ' . $search . ' not found!');
            }
        }
    } else {
        $smarty->addTlpParam('errormessage', 'Category not given!');
    }
} else {
    $smarty->addTlpParam('errormessage', 'Page not given!');
}
$smarty->display();
$db->disconnect();