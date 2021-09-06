<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\UserDataTransferObject;

class UserMapper
{
    public function map(array $user): UserDataTransferObject
    {
        $userDTO = new UserDataTransferObject();

        $userDTO->id = $user['UserID'] ?? '';
        $userDTO->username = $user['Username'] ?? '';
        $userDTO->password = $user['Password'] ?? '';

        return $userDTO;
    }
}