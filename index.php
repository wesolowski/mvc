<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/vendor/autoload.php";


    /*
    $page = "\App\Controller\\"; //Namespace
    $page .= $_GET['page'];
    $smarty = new Smarty();
    $page = new $page($smarty);
    $page->action();
    */
    $smarty = new \App\Controller\SmartyView(new Smarty());
    $search = $_GET['page'] ?? '';
    if ($search === 'Category') {
        $page = new \App\Controller\Category($smarty);
        $page->action();
    } elseif ($search === 'Details') {
        $page = new \App\Controller\Detail($smarty);
        $page->action();
    } elseif ($search === 'Home') {
        $page = new \App\Controller\Home($smarty);
        $page->action();
    } else {
        echo "Error 404";
    }

