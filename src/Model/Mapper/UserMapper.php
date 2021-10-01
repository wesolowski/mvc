<?php
declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\Dto\UserDataTransferObject;
use App\Model\ORMEntity\User;

class UserMapper
{
    public function map(User $user): UserDataTransferObject
    {
        $userDTO = new UserDataTransferObject();

        $userDTO->id = $user->getId();

        $userDTO->name = $user->getName();

        $userDTO->password = $user->getPassword();

        return $userDTO;
    }
}