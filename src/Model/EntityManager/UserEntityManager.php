<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Repository\UserRepository;

class UserEntityManager implements EntityManagerInterface
{
    private \PDO $connection;
    private UserRepository $userRepository;

    public function __construct(Database $database, UserRepository $userRepository)
    {
        $this->connection = $database->getConnection();
        $this->userRepository = $userRepository;
    }

    public function insert(array $data = []): ?string
    {
        $returnMessage = null;
        //Checks if User & Passwort set
        if (isset($data['username'], $data['password']) && $data['username'] !== '' && $data['password'] !== '') {
            //Checks if User exists
            if ($this->userRepository->getByUsername($data['username']) === null) {
                $query = $this->connection->prepare('INSERT INTO User (Username, Password) VALUES (?, ?)');
                $query->execute([$data['username'], $data['password']]);
            } else {
                $returnMessage = "User already exists";
            }
        } else {
            $returnMessage = "User and Password musst be given";
        }
        return $returnMessage;
    }

    public function update(array $data = []): ?string
    {
        $returnMessage = null;
        if (isset($data['username'], $data['password'], $data['id']) && $data['username'] !== '' && $data['password'] !== '' && $data['id'] !== '') {
            if($this->userRepository->getByID($data['id']) !== null) {
                $query = $this->connection->prepare('UPDATE User SET Username = ?, Password = ? WHERE UserID = ? LIMIT 1');
                $query->execute([$data['username'], $data['password'], $data['id']]);
            }else{
                $returnMessage = "User does not exist";
            }
        } else {
            $returnMessage = "User, Password and ID musst be given";
        }
        return $returnMessage;
    }

    public function delete(array $data  = []): ?string
    {
        $returnMessage = null;
        if(isset($data['id']) && $data['id'] !== ''){
            $query = $this->connection->prepare('DELETE FROM User WHERE UserID = ?');
            $query->execute([$data['id']]);
        }
        else{
            $returnMessage = "Id musst be given";
        }
        return $returnMessage;
    }
}