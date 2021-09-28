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
        $name = trim($user['name']) ?? '';
        $password = trim($user['password']) ?? '';

        if ($name === '') {
            $errors[] = 'Username musst be given';
        }
        if ($password === '') {
            $errors[] = 'Password musst be given';
        }
        if (empty($errors)) {
            $userDTO = $this->userRepository->getByUsername($name);
            if (($userDTO instanceof UserDataTransferObject) && $name === $userDTO->name && $password === $userDTO->password) {
                return [];
            } else {
                $errors[] = "Password and User don't match";
            }
        }
        return $errors;
    }
}