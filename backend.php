<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";

$smarty = new \App\Core\SmartyView(new Smarty());
$provider = new \App\Core\ControllerProvider();
$userRepository = new \App\Model\UserRepository();
$search = $_GET['page'] ?? 'Home';

//Schaut ob Klasse vorhanden ist und ruft passende auf
foreach ($provider->getList() as $className) {
    if ('App\Controller\\' . $search === $className) {
        $page = new $className($smarty, $userRepository);

        if (!$page instanceof \App\Controller\ControllerInterface) {
            throw new RuntimeException('Class ' . $className . ' is not instace of ' . \App\Controller\ControllerInterface::class);
        }

        $page->action();
    }
}
$smarty->display();