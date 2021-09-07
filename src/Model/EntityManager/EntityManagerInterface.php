<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

interface EntityManagerInterface
{
    public function insert(array $data):?string;
    public function update(array $data):?string;
    public function delete(string $id):?string;
}
