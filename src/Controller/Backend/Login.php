<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\UserRepository;
use App\Controller\ControllerInterface;

class Login implements ControllerInterface
{
    private UserRepository $userRepository;
    private ViewInterface $smartyController;
    private Redirect $redirect;
    private AdminLogin $adminLogin;

    public function __construct(ViewInterface $smartyController, UserRepository $userRepository, Redirect $redirect)
    {
        if(isset($_SESSION['user'])){
            $_SESSION = [];
        }
        $this->smartyController = $smartyController;
        $this->userRepository = $userRepository;
        $this->redirect = $redirect;
        $this->adminLogin = new AdminLogin($userRepository);
    }

    public function action(): void
    {
        if (isset($_POST['login'])) {
            $errors = $this->adminLogin->validation(['username' => $_POST['username'], 'password' => $_POST['password']]);
            if (!empty($errors)) {
                $this->smartyController->addTlpParam('username', $_POST['username']);
                $this->smartyController->addTlpParam('errors', $errors);
                $this->smartyController->addTlpParam('errorh3', 'Errors:');
            } else {
                $_SESSION['user'] = ['username' => $_POST['username'], 'password' => $_POST['password']];
                $_POST = []; //Emptys Post
                $this->redirect->redirect('index.php?page=Home&area=Admin');
            }
        }
        $this->smartyController->addTlpParam('footerLink', 'Home');
        $this->smartyController->addTlpParam('footerLinkName', 'Public - Home');
        $this->smartyController->addTemplate('backend/login.tpl');
    }
}