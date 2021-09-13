<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Mapper\UserMapper;
use App\Model\Dto\UserDataTransferObject;
use App\Model\Database;

class UserRepository
{
    private Database $db;
    private UserMapper $userMapper;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->userMapper = new UserMapper();
    }

    public function getByID(int $id): ?UserDataTransferObject
    {
        $mappedUser = null;
        $query = $this->db->getConnection()->prepare("SELECT * FROM User WHERE UserID = ?");
        $query->execute([$id]);
        while ($user = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedUser = $this->userMapper->map($user);
        }
        return $mappedUser;
    }

    public function getByUsername(string $username): ?UserDataTransferObject
    {
        $mappedUser = null;
        $query = $this->db->getConnection()->prepare("SELECT * FROM User WHERE Username = ?");
        $query->execute([$username]);
        while ($user = $query->fetch(\PDO::FETCH_ASSOC)) {
            $mappedUser = $this->userMapper->map($user);
        }
        return $mappedUser;
    }
}