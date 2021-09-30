<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

$doctrine = new \App\Model\Doctrine();

$entityManager = $doctrine->getEntityManager();

/*
$newUserName = $argv[1];

$user = new \App\Model\ORMEntityManager\User();
$user->setName($newUserName);
$user->setPassword($newUserName);

$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID " . $user->getId() . "\nName " . $user->getName() . "\n";
*/


$dql = "SELECT u FROM App\Model\ORMEntityManager\User u";

$query = $entityManager->createQuery($dql);
$users = $query->getResult();

foreach ($users as $user) {
    echo "Id: " . $user->getId() . "\nName: " . $user->getName() . "\n--------------------\n";
}
