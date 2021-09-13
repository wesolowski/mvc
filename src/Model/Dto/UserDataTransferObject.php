<?php
declare(strict_types=1);

namespace App\Model\Dto;

class UserDataTransferObject
{
    public int $id;
    public string $username;
    public string $password;
}