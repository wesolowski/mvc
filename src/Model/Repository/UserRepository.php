<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Mapper\UserMapper;
use App\Model\Dto\UserDataTransferObject;
use App\Model\Database;
use Doctrine\ORM\EntityManager;

class UserRepository
{
    private EntityManager $entityManager;
    private UserMapper $userMapper;

    public function __construct(EntityManager $entityManager, UserMapper $userMapper)
    {
        $this->entityManager = $entityManager;
        $this->userMapper = $userMapper;
    }

    public function getByID(int $id): ?UserDataTransferObject
    {
        $user = $this->entityManager->getRepository('User')
                                    ->find(array('id' => $id));

        if (empty($user)) {
            return null;
        }

        return $this->userMapper->map($user);
    }

    public function getByUsername(string $name): ?UserDataTransferObject
    {
        $user = $this->entityManager->getRepository('User')
            ->find(array('name' => $name));

        if (empty($user)) {
            return null;
        }

        return $this->userMapper->map($user);
    }
}