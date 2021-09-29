<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\UserDataTransferObject;

class UserMapper
{
    public function map(array $user): UserDataTransferObject
    {
        $userDTO = new UserDataTransferObject();

        if (isset($user['id'])){
            $userDTO->id = (int)$user['id'];
        } else {
            $userDTO->id = 0;
        }

        $userDTO->name = $user['name'] ?? '';
        $userDTO->password = $user['password'] ?? '';

        return $userDTO;
    }
}