<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\Container;
use App\Core\View\ViewInterface;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Repository\CategoryRepository;

class Category implements BackendControllerInterface
{
    private CategoryRepository $categoryRepository;
    private ViewInterface $viewInterface;
    private CategoryEntityManager $categoryEntityManager;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->categoryEntityManager = $container->get(CategoryEntityManager::class);
    }

    public function action(): void
    {
        if(isset($_POST['createCategory'])){
            $newCategoryName = $_POST['newCategoryName'] ?? '';
            if($newCategoryName === ''){
                $this->viewInterface->addTlpParam('error', 'Product Name musst be given');
            } else {
                $this->categoryEntityManager->insert(['categoryname' => $newCategoryName]);
                $_POST = [];
            }
        }
        $this->viewInterface->addTlpParam('categoryList', $this->categoryRepository->getList());
        $this->viewInterface->addTemplate('backend/product.tpl');
    }
}