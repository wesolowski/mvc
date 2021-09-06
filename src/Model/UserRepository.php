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
        global $db;
        $userQuery = $db->getConnection()->query("SELECT * FROM User");
        while ($user = $userQuery->fetch(\PDO::FETCH_ASSOC)) {
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