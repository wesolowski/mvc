<?php
declare(strict_types=1);

use \App\Core\SmartyView;
use \App\Core\ControllerProvider;
use \App\Model\ProductRepository;
use \App\Controller\ControllerInterface;
use \App\Model\UserRepository;


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

$smarty = new SmartyView(new Smarty());
$provider = new ControllerProvider();
$repositoryType = new ProductRepository();
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
        $page = new $className($smarty, $repositoryType);

        if (!$page instanceof ControllerInterface) {
            throw new RuntimeException('Class ' . $className . ' is not instance of ' . ControllerInterface::class);
        }

        $page->action();
    }
}
$smarty->display();