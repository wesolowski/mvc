<?php
declare(strict_types=1);

namespace App\Core;

use App\Model\Dto\UserDataTransferObject;
use App\Model\Repository\UserRepository;

class UserValidation
{
    private UserRepository $userRepository;
    private array $errors = [];

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    private function empty(string $type, string $value): void
    {
        if ($value === '') {
            $this->errors[] = $type . ' musst be given';
        }
    }

    public function validation(array $user = []): array
    {
        $name = trim($user['name']) ?? '';
        $password = trim($user['password']) ?? '';

        $this->empty('Username', $name);
        $this->empty('Password', $password);

        if (empty($this->errors)) {
            $userDTO = $this->userRepository->getByUsername($name);
            if (($userDTO instanceof UserDataTransferObject) && $name === $userDTO->name && $password === $userDTO->password) {
                return [];
            } else {
                $this->errors[] = "Password and User don't match";
            }
        }
        return $this->errors;
    }
}