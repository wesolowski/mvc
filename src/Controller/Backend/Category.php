<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\UserRepository;

class Category implements ControllerInterface
{
    private CategoryRepository $categoryRepository;
    private UserRepository $userRepository;
    private ViewInterface $smartyController;
    private Redirect $redirect;

    public function __construct(ViewInterface $smartyController, array $repositoryType, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->categoryRepository = $repositoryType['categoryRepository'];
        $this->userRepository = $repositoryType['userRepository'];

        $this->redirect = $redirect;
        $adminLogin = new AdminLogin($this->userRepository);

        if ($adminLogin->loggedIn() === false) {
            $redirect->redirect('index.php?page=a$Login');
        }
    }

    public function action(): void
    {
        $this->smartyController->addTlpParam('categoryList', $this->categoryRepository->getList());
        $this->smartyController->addTemplate('backend/category.tpl');
    }
}