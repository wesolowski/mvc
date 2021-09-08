<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\Repository\UserRepository;

class Home implements ControllerInterface
{
    private UserRepository $userRepository;
    private ViewInterface $smartyController;
    private Redirect $redirect;

    public function __construct(ViewInterface $smartyController, array $repositoryType, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->userRepository = $repositoryType['userRepository'];
        $this->redirect = $redirect;
        $adminLogin = new AdminLogin($this->userRepository);

        if($adminLogin->loggedIn() === false){
            $redirect->redirect('index.php?page=a$Login');
        }
    }

    public function action(): void
    {
        $footerLink = ['link' => '?page=a$Login', 'name' => 'Admin - Logout'];
        $this->smartyController->addTlpParam('footerLink', $footerLink);
        $this->smartyController->addTlpParam('username', $_SESSION['user']['username']);
        $this->smartyController->addTemplate('backend/home.tpl');
    }
}