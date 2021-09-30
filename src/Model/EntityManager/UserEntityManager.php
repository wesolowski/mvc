<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Dto\UserDataTransferObject;
use Doctrine\ORM\EntityManager;

//TODO change to ORM
class UserEntityManager
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function insert(UserDataTransferObject $userDTO): void
    {
        $user = new \App\Model\ORMEntityManager\User();
        $user->setName($userDTO->name);
        $user->setPassword($userDTO->password);
    }

    public function update(UserDataTransferObject $userDTO): void
    {
        /*TODO
        $query = $this->connection->prepare('UPDATE user SET name = ?, password = ? WHERE id = ? LIMIT 1');
        $query->execute([$userDTO->name, $userDTO->password, $userDTO->id]);
        */
    }

    public function delete(int $id): void
    {
        /* TODO
        $query = $this->connection->prepare('DELETE FROM user WHERE id = ?');
        $query->execute([$id]);
        */
    }
}