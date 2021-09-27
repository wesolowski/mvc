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
            $name = $_SESSION['user']['name'] ?? '';
            $password = $_SESSION['user']['password'] ?? '';
            $userDTO = $this->userRepository->getByUsername($name);
            if (($userDTO !== null) && $name === $userDTO->name && $password === $userDTO->password) {
                return true;
            } else{
                unset($_SESSION);
                return false;
            }
        }
        unset($_SESSION);
        return false;
    }
}