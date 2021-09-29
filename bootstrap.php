<?php
declare(strict_types=1);

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;

$conn = [
    'dbname' => 'mvc',
    'user' => 'philipp',
    'password' => 'nexusval',
    'host' => 'localhost',
    'charset' => 'utf8mb4',
    'driver' => 'pdo_sqlite',
];

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

//$entityManager = EntityManager::create($conn, $config);
try {
    $entityManager = EntityManager::create($conn, $config);
} catch (\Doctrine\ORM\ORMException $e) {
}