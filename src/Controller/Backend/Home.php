<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\UserRepository;

class Home implements ControllerInterface
{
    private UserRepository $userRepository;
    private ViewInterface $smartyController;
    private Redirect $redirect;

    public function __construct(ViewInterface $smartyController, UserRepository $userRepository, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->userRepository = $userRepository;
        $this->redirect = $redirect;
        $adminLogin = new AdminLogin($userRepository);

        if($adminLogin->loggedIn() === false){
            $redirect->redirect('index.php?page=a$Login');
        }
    }

    public function action(): void
    {
        $footerLink = ['link' => 'a$Login', 'name' => 'Admin - Logout'];
        $this->smartyController->addTlpParam('footerLink', $footerLink);
        $this->smartyController->addTlpParam('username', $_SESSION['user']['username']);
        $this->smartyController->addTemplate('backend/home.tpl');
    }
}