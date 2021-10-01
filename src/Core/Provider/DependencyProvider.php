<?php
declare(strict_types=1);

namespace App\Core\Provider;

use App\Core\AdminLogin;
use App\Core\Container;
use App\Core\Redirect\Redirect;
use App\Core\Redirect\RedirectInterface;
use App\Core\UserValidation;
use App\Core\View\SmartyView;
use App\Core\View\ViewInterface;
use App\Model\Database;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\EntityManager\UserEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Mapper\ProductMapper;
use App\Model\Mapper\UserMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;
use App\Model\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

class DependencyProvider
{
    public function provide(Container $container, EntityManager $entityManager): void
    {
        $container->set(ViewInterface::class, new SmartyView(new \Smarty()));
        $container->set(RedirectInterface::class, new Redirect());
        //Mapper
        $container->set(UserMapper::class, new UserMapper());
        $container->set(CategoryMapper::class, new CategoryMapper());
        $container->set(ProductMapper::class, new ProductMapper());
        //Repositorys
        $container->set(UserRepository::class, new UserRepository($entityManager, $container->get(UserMapper::class)));
        $container->set(CategoryRepository::class, new CategoryRepository($entityManager, $container->get(CategoryMapper::class)));
        $container->set(ProductRepository::class, new ProductRepository($entityManager, $container->get(ProductMapper::class)));
        //EntityManager
        $container->set(UserEntityManager::class, new UserEntityManager($entityManager));
        $container->set(CategoryEntityManager::class, new CategoryEntityManager($entityManager));
        $container->set(ProductEntityManager::class, new ProductEntityManager($entityManager));
        $container->set(CategoryProductEntityManager::class, new CategoryProductEntityManager($entityManager));
        //Others
        $container->set(UserValidation::class, new UserValidation($container->get(UserRepository::class)));
        $container->set(AdminLogin::class, new AdminLogin($container->get(UserRepository::class)));
    }
}