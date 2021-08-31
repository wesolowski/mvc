<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\UserRepository;

class Login implements ControllerInterface
{
    private UserRepository $userRepository;
    private ViewInterface $smartyController;
    public function __construct(ViewInterface $smartyController, UserRepository $userRepository)
    {
        $this->smartyController = $smartyController;
        $this->userRepository = $userRepository;
    }

    public function action(): void
    {
        if(isset($_POST['login']))
        {
            $errors = $this->validation(['username' => $_POST['username'], 'password' => $_POST['password']]);
            $this->smartyController->addTlpParam('errors', $errors);

            $username = $_POST['username'] ?? '';
            $this->smartyController->addTlpParam('username', $username);
        }else {
            $this->smartyController->addTlpParam('username', '');
        }
        $this->smartyController->addTemplate('backendLogin.tpl');
    }

    public function validation(array $user = []): array
    {
        $errors = [];

        $username = trim($user['username']) ?? '';
        $password = trim($user['password']) ?? '';

        if($username === '')
        {
            $errors[] = 'User musst be given';
        }
        if($password === '')
        {
            $errors[] = 'Password musst be given';
        }
        if(empty($errors)){
            $getByUsername = $this->userRepository->getByUsername($username);

            if($username === $getByUsername->username && $password === $getByUsername->password){

                header("Location: Backend.php?page=BackendAdminPage");
                exit();
            }
            else{
                $errors[] = "Password and User don't match";
            }
        }
        return $errors;
    }
}