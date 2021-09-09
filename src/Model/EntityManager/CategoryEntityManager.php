<?php
declare(strict_types=1);

namespace App\Model\EntityManager;

use App\Controller\Frontend\Category;
use App\Core\Redirect;
use App\Model\Repository\CategoryRepository;
use App\Model\Database;
use App\Model\Repository\ProductRepository;
use function React\Promise\map;

class CategoryEntityManager implements EntityManagerInterface
{
    private \PDO $connection;
    private Database $database;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;

    public function __construct(Database $database, CategoryRepository $categoryRepository)
    {
        $this->database = $database;
        $this->connection = $this->database->getConnection();
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

    public function delete(array $data  = []): ?string
    {
        $returnMessage = null;
        if(isset($data['id'], $data['productRepositoryList']) && $data['id'] !== ''){
            if(empty($data['productRepositoryList'])){
                $query = $this->connection->prepare('DELETE FROM Category WHERE CategoryID = ?');
                $query->execute([$data['id']]);
                $this->categoryRepository->map();
            }
        }
        else{
            $returnMessage = "Id musst be given";
        }
        return $returnMessage;
    }
}