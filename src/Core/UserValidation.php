<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Repository\UserRepository;

class UserValidation
{
    private UserRepository $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validation(array $user = []): array
    {
        $errors = [];

        $username = trim($user['username']) ?? '';
        $password = trim($user['password']) ?? '';

        if ($username === '') {
            $errors[] = 'User musst be given';
        }
        if ($password === '') {
            $errors[] = 'Password musst be given';
        }
        if (empty($errors)) {
            $getByUsername = $this->userRepository->getByUsername($username);
            if (($getByUsername !== null) && $username === $getByUsername->username && $password === $getByUsername->password) {
                return $errors;
            } else {
                $errors[] = "Password and User don't match";
            }
        }
        return $errors;
    }
}