<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Repository\UserRepository;
use phpDocumentor\Reflection\PseudoTypes\False_;

class UserEntityManager
{
    private PDO $connection;
    private UserRepository $userRepository;

    public function __construct(Database $database, UserRepository $userRepository)
    {
        $this->connection = $database->getConnection();
        $this->userRepository = new UserRepository($database);
    }

    public function insertUser(array $user = []): ?string
    {
        $returnMessage = null;
        //Checks if User & Passwort set
        if (isset($user['username'], $user['password']) && $user['username'] !== '' && $user['password'] !== '') {
            //Checks if User exists
            if ($this->userRepository->getByUsername($user['username']) === null) {
                $query = $this->connection->prepare('INSERT INTO User (Username, Password) VALUES (?, ?)');
                $query->execute([$user['username'], $user['password']]);
            } else {
                $returnMessage = "User already exists";
            }
        } else {
            $returnMessage = "User and Password musst be given";
        }
        return $returnMessage;
    }

    public function updateUser(array $user = []): ?string
    {
        $returnMessage = null;
        if (isset($user['username'], $user['password']) && $user['username'] !== '' && $user['password'] !== '') {
            //TODO check if user exists
            $query = $this->connection->prepare('UPDATE User SET Username = ?
                                                                        ,Password = ?
                                                                   WHERE UserID = ?');
            $query->execute([$user['username'], $user['password'], $user['id']]);
        } else {
            $returnMessage = "User and Password musst be given";
        }
        return $returnMessage;
    }

    public function deleteUser(string $id): ?string
    {
        $returnMessage = null;
        if($id !== ''){
            $query = $this->connection->prepare('DELETE FROM User WHERE UserID = ?');
            $query->execute([$id]);
        }
        else{
            $returnMessage = "Id musst be given";
        }
        return $returnMessage;
    }
}