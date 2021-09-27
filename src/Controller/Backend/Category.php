<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\Container;
use App\Core\View\ViewInterface;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\Mapper\CategoryMapper;
use App\Model\Repository\CategoryRepository;

class Category implements BackendControllerInterface
{
    private CategoryRepository $categoryRepository;
    private ViewInterface $viewInterface;
    private CategoryEntityManager $categoryEntityManager;
    private CategoryMapper $categoryMapper;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->categoryEntityManager = $container->get(CategoryEntityManager::class);
        $this->categoryMapper = $container->get(CategoryMapper::class);
    }

    public function action(): void
    {
        if(isset($_POST['createCategory'])){
            $createName = trim($_POST['createName']) ?? '';
            if($createName === ''){
                $this->viewInterface->addTlpParam('error', 'Category Name musst be given');
            } else {
                $categoryDTO = $this->categoryMapper->map(['CategoryName' => $createName]);
                $this->categoryEntityManager->insert($categoryDTO);
            }
        }
        $this->viewInterface->addTlpParam('categoryDTOList', $this->categoryRepository->getList());
        $this->viewInterface->addTemplate('backend/category.tpl');
    }
}