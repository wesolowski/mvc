<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Dto\UserDataTransferObject;
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
        $username = $user['username'] ?? '';
        $password = $user['password'] ?? '';

        if (trim($username) === '') {
            $errors[] = 'User musst be given';
        }
        if (trim($password) === '') {
            $errors[] = 'Password musst be given';
        }
        if (empty($errors)) {
            $userDTO = $this->userRepository->getByUsername(trim($username));
            if (($userDTO instanceof UserDataTransferObject) && $username === $userDTO->username && $password === $userDTO->password) {
                return $errors;
            } else {
                $errors[] = "Password and User don't match";
            }
        }
        return $errors;
    }
}