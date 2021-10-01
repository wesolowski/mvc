<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Model\Database;
use App\Model\Dto\UserDataTransferObject;
use App\Model\ORMEntity\User;
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
        $user = new User();
        $user->setName($userDTO->name);
        $user->setPassword($userDTO->password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(UserDataTransferObject $userDTO): void
    {
        $user = $this->entityManager->getReference('User', $userDTO->id);
        $user->setName($userDTO->name);
        $user->setPassword($userDTO->password);

        $this->entityManager->flush();
    }

    public function delete(int $id): void
    {
        $user = $this->entityManager->getReference('User', $id);
        $this->entityManager->remove($user);

        $this->entityManager->flush();
    }
}