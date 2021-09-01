<?php
declare(strict_types=1);

namespace App\Model;

use App\Model\Mapper\UserMapper;
use App\Model\Dto\UserDataTransferObject;

class UserRepository
{
    private array $userDataTransferObjectList;

    public function __construct()
    {
        $path = file_get_contents(__DIR__ . '/User.json');
        $list = json_decode($path, true);
        if (json_last_error()) {
            exit("json error: " . json_last_error_msg() . " (" . json_last_error() . ")");
        }
        foreach ($list as $user){
            $userMapper = new UserMapper();
            $mappedUser = $userMapper->map($user);
            $this->userDataTransferObjectList[$mappedUser->username] = $mappedUser;
        }
    }

    public function getByUsername(string $username): ?UserDataTransferObject
    {
        if($this->hasUser($username) === false){
            return null;
        }
        return $this->userDataTransferObjectList[$username];
    }

    public function hasUser(string $username): bool
    {
        return isset($this->userDataTransferObjectList[$username]);
    }
    /* Werden noch nicht gebraucht
    private function getFromDB(string $data): void
    {
    }

    private function makeArrayResult(): array
    {
    }
    */
}