<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Repository\UserRepository;

class AdminLogin
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loggedIn(): bool{
        if(isset($_SESSION['user'])) {
            $username = $_SESSION['user']['username'];
            $password = $_SESSION['user']['password'];
            $getByUsername = $this->userRepository->getByUsername($username);
            if (($getByUsername !== null) && $username === $getByUsername->username && $password === $getByUsername->password) {
                return true;
            }
            else{
                $_SESSION = [];
                return false;
            }
        }
        $_SESSION = [];
        return false;
    }
}