<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Mapper\UserMapper;
use App\Model\Dto\UserDataTransferObject;
use App\Model\Database;

class UserRepository
{
    private Database $database;
    private UserMapper $userMapper;

    public function __construct(Database $database, UserMapper $userMapper)
    {
        $this->database = $database;
        $this->userMapper = $userMapper;
    }

    public function getByID(int $id): ?UserDataTransferObject
    {
        $query = $this->database->getConnection()->prepare("SELECT * FROM user WHERE id = ? LIMIT 1");
        $query->execute([$id]);

        $user = $query->fetch(\PDO::FETCH_ASSOC);

        if(empty($user)) {
            return null;
        }

        return $this->userMapper->map($user);
    }

    public function getByUsername(string $name): ?UserDataTransferObject
    {
        $query = $this->database->getConnection()->prepare("SELECT * FROM user WHERE name = ? LIMIT 1");
        $query->execute([$name]);

        $user = $query->fetch(\PDO::FETCH_ASSOC);

        if(empty($user)) {
            return null;
        }

        return $this->userMapper->map($user);
    }
}