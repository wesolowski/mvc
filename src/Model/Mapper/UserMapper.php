<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\UserDataTransferObject;

class UserMapper
{
    public function map(array $user): UserDataTransferObject
    {
        $userDTO = new UserDataTransferObject();

        if(isset($user['UserID'])){
            $userDTO->id = (int)$user['UserID'];
        } else {
            $userDTO->id = 0;
        }
        $userDTO->username = $user['Username'] ?? '';
        $userDTO->password = $user['Password'] ?? '';

        return $userDTO;
    }
}