<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Mapper\UserMapper;
use App\Model\Dto\UserDataTransferObject;
use App\Model\Database;

class UserRepository
{
    private array $userDataTransferObjectList;
    private array $userDataTransferObjectListUsingID;
    private Database $db;
    private UserMapper $userMapper;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $userQuery = $this->db->getConnection()->query("SELECT * FROM User");
        while ($user = $userQuery->fetch(\PDO::FETCH_ASSOC)) {
            $this->userMapper = new UserMapper();
            $mappedUser = $this->userMapper->map($user);
            $this->userDataTransferObjectList[$mappedUser->username] = $mappedUser;
            $this->userDataTransferObjectListUsingID[$mappedUser->id] = $mappedUser;
        }
    }

    public function map(): void
    {
        $this->userDataTransferObjectList = [];
        $userQuery = $this->db->getConnection()->query("SELECT * FROM User");
        while ($user = $userQuery->fetch(\PDO::FETCH_ASSOC)) {
            $mappedUser = $this->userMapper->map($user);
            $this->userDataTransferObjectList[$mappedUser->username] = $mappedUser;
            $this->userDataTransferObjectListUsingID[$mappedUser->id] = $mappedUser;
        }
    }

    public function getByUsername(string $username): ?UserDataTransferObject
    {
        if ($this->hasUser(['username' => $username]) === false) {
            return null;
        }
        return $this->userDataTransferObjectList[$username];
    }

    public function getByID(string $id): ?UserDataTransferObject
    {
        if ($this->hasUser(['id' => $id]) === false) {
            return null;
        }
        return $this->userDataTransferObjectListUsingID[$id];
    }

    public function hasUser(array $check = []): bool
    {
        $isset = false;
        if(isset($check['username'])) {
            $isset = isset($this->userDataTransferObjectList[$check['username']]);
        }elseif(isset($check['id'])){
            $isset = isset($this->userDataTransferObjectListUsingID[$check['id']]);
        }
        return $isset;
    }
}