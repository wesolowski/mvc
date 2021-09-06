<?php
declare(strict_types=1);
session_start();

use \App\Core\SmartyView;
use \App\Core\ControllerProvider;
use \App\Model\ProductRepository;
use \App\Model\UserRepository;
use \App\Core\Redirect;
use \App\Model\CategoryRepository;
use App\Controller\ControllerInterface;
use App\Model\Database;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

$db = new Database();
$db->connect();

$smarty = new SmartyView(new Smarty());
$provider = new ControllerProvider();
$repositoryType = null;
$redirect = new Redirect();

$providerType = null;
$searchNamespace = 'App\Controller\\';

$search = $_GET['page'] ?? 'h$Home';
$searchExplode = explode('$', $search);

if (count($searchExplode) === 2) {
    if ($searchExplode[0] === 'h') {
        $searchNamespace .= 'Frontend\\';
        $providerType = $provider->getFrontendList();
        $repositoryType = new CategoryRepository($db);
    } elseif ($searchExplode[0] === 'c' || $searchExplode[0] === 'p') {
        $searchNamespace .= 'Frontend\\';
        $providerType = $provider->getFrontendList();
        $category = $_GET['category'] ?? '';
        $repositoryType = new ProductRepository($category, $redirect, $db);
    } elseif ($searchExplode[0] === 'a') {
        $searchNamespace .= 'Backend\\';
        $providerType = $provider->getBackendList();
        $repositoryType = new UserRepository($db);
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