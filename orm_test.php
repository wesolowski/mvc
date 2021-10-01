<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

$doctrine = new \App\Model\Doctrine();

$entityManager = $doctrine->getEntityManager();

/*
$user = new \App\Model\ORMEntity\Category();
$user->setName('ORMCategory');

$entityManager->persist($user);
$entityManager->flush();

$user = new \App\Model\ORMEntityManager\Product();
$user->setName('ORMProduct');

$entityManager->persist($user);
$entityManager->flush();


$user = new \App\Model\ORMEntity\CategoryProduct();
$user->setCategoryId(1);
$user->setProductId(12);
$entityManager->persist($user);
$entityManager->flush();

*/
$user = new \App\Model\ORMEntity\User();
$user->setName('test');
$user->setPassword('lol');
$entityManager->persist($user);
$entityManager->flush();

$users = $entityManager->getRepository('\App\Model\ORMEntity\User')->findAll();

foreach ($users as $user){
    echo $user->getName();
}