<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

$doctrine = new \App\Model\Doctrine();

$entityManager = $doctrine->getEntityManager();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);