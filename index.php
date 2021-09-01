<?php
declare(strict_types=1);
session_start();

use \App\Core\SmartyView;
use \App\Core\ControllerProvider;
use \App\Model\ProductRepository;
use \App\Controller\ControllerInterface;
use \App\Model\UserRepository;
use \App\Core\Redirect;


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

$smarty = new SmartyView(new Smarty());
$provider = new ControllerProvider();
$repositoryType = new ProductRepository();
$redirect = new Redirect();

$providerType = $provider->getFrontendList();
$searchNamespace = 'App\Controller\\';

$search = $_GET['page'] ?? 'Home';

if (isset($_GET['area']) && $_GET['area'] === 'Admin') {
    $searchNamespace .= 'Backend\\';
    $providerType = $provider->getBackendList();
    $repositoryType = new UserRepository();
} else {
    $searchNamespace .= 'Frontend\\';
}

foreach ($providerType as $className) {
    if ($searchNamespace . $search === $className) {
        $page = new $className($smarty, $repositoryType, $redirect);

        if (!$page instanceof ControllerInterface) {
            throw new RuntimeException('Class ' . $className . ' is not instance of ' . ControllerInterface::class);
        }

        $page->action();
    }
    else{
        if(isset($_GET['page']) && isset($_GET['area']))
        {
            $smarty->addTlpParam('errormessage', $search . ' not founde in area '. $_GET['area'] .'!');
        }
        elseif(isset($_GET['page']))
        {
            $smarty->addTlpParam('errormessage', $search . ' not founde!');
        }
        elseif(isset($_GET['area']))
        {
            $smarty->addTlpParam('errormessage', 'Only area: ' . $_GET['area'] . ' given, page musst be given too!');
        }
        else{
            $smarty->addTlpParam('errormessage', 'No page given!');
        }
    }
}
$smarty->display();