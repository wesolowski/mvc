<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\AdminLogin;
use App\Core\Redirect;
use App\Core\UserValidation;
use App\Core\ViewInterface;
use App\Model\Repository\UserRepository;
use App\Controller\ControllerInterface;

class Login implements ControllerInterface
{
    private ViewInterface $smartyController;
    private Redirect $redirect;
    private UserValidation $userValidation;

    public function __construct(ViewInterface $smartyController, array $repositoryType, Redirect $redirect)
    {
        if (isset($_SESSION['user'])) {
            $_SESSION = [];
        }
        $this->smartyController = $smartyController;
        $this->redirect = $redirect;
        $this->userValidation = new UserValidation($repositoryType['userRepository']);
    }

    public function action(): void
    {
        if (isset($_POST['login'])) {
            $errors = $this->userValidation->validation(['username' => $_POST['username'], 'password' => $_POST['password']]);

            if (!empty($errors)) {
                //TODO if abfrage in smarty
                $this->smartyController->addTlpParam('username', $_POST['username']);
                $this->smartyController->addTlpParam('errors', $errors);
                $this->smartyController->addTlpParam('errorh3', 'Errors:');
            } else {
                $_SESSION['user'] = ['username' => $_POST['username'], 'password' => $_POST['password']];
                $_POST = []; //Emptys Post
                $this->redirect->redirect('index.php?page=a$Home');
            }
        }
        $footerLink = ['link' => ' ', 'name' => 'Public - Home'];
        $this->smartyController->addTlpParam('footerLink', $footerLink);
        $this->smartyController->addTemplate('backend/login.tpl');
    }
}