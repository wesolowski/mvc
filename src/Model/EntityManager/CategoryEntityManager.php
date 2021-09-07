<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Controller\Frontend\Category;
use App\Model\Repository\CategoryRepository;
use App\Model\Database;
use function React\Promise\map;

class CategoryEntityManager implements EntityManagerInterface
{
    private \PDO $connection;
    private CategoryRepository $categoryRepository;

    public function __construct(Database $database, CategoryRepository $categoryRepository)
    {
        $this->connection = $database->getConnection();
        $this->categoryRepository = $categoryRepository;
    }

    public function insert(array $data = []): ?string
    {
        $returnMessage = null;
        if (isset($data['categoryname']) && $data['categoryname'] !== '') {
            if($this->categoryRepository->hasCategory(['categoryname' => $data['categoryname']]) === false){
                $query = $this->connection->prepare('INSERT INTO Category (CategoryName) VALUES (?)');
                $query->execute([$data['categoryname']]);
                $this->categoryRepository->map();
            } else {
                $returnMessage = "Category already exists";
            }
        } else {
            $returnMessage = "Category musst be given";
        }
        return $returnMessage;
    }

    public function update(array $data = []): ?string
    {
        $returnMessage = null;
        if (isset($data['categoryname'], $data['id']) && $data['categoryname'] !== '' && $data['id'] !== '') {
            if($this->categoryRepository->hasCategory(['id' => $data['id']]) === true){
                $query = $this->connection->prepare('UPDATE Category SET CategoryName = ? WHERE CategoryID = ? LIMIT 1');
                $query->execute([$data['categoryname'], $data['id']]);
                $this->categoryRepository->map();
            } else {
                $returnMessage = "Category doesnt exist";
            }
        } else {
            $returnMessage = "Category and ID musst be given";
        }
        return $returnMessage;
    }

    public function delete(string $id): ?string
    {
        $returnMessage = null;
        if($id !== ''){
            $query = $this->connection->prepare('DELETE FROM Category WHERE CategoryID = ?');
            $query->execute([$id]);
            $this->categoryRepository->map();
        }
        else{
            $returnMessage = "Id musst be given";
        }
        return $returnMessage;
    }
}