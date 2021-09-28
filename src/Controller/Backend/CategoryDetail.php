<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\View\ViewInterface;
use App\Model\Dto\CategoryDataTransferObject;
use App\Model\EntityManager\CategoryEntityManager;
use App\Model\EntityManager\CategoryProductEntityManager;
use App\Model\EntityManager\ProductEntityManager;
use App\Model\Mapper\ProductMapper;
use App\Model\Repository\CategoryRepository;
use App\Model\Repository\ProductRepository;

class CategoryDetail implements BackendControllerInterface
{
    private ViewInterface $viewInterface;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private CategoryEntityManager $categoryEntityManager;
    private ProductEntityManager $productEntityManager;
    private CategoryProductEntityManager $categoryProductEntityManager;
    private ProductMapper $productMapper;
    private RedirectInterface $redirect;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->categoryRepository = $container->get(CategoryRepository::class);
        $this->productRepository = $container->get(ProductRepository::class);
        $this->categoryEntityManager = $container->get(CategoryEntityManager::class);
        $this->productEntityManager = $container->get(ProductEntityManager::class);
        $this->categoryProductEntityManager = $container->get(CategoryProductEntityManager::class);
        $this->productMapper = $container->get(ProductMapper::class);
        $this->redirect = $container->get(RedirectInterface::class);
    }

    public function action(): void
    {
        $errors = [];
        $create = [];

        if (!isset($_GET['categoryId']) || $_GET['categoryId'] === '') {
            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }
        $categoryId = (int)$_GET['categoryId'];
        $categoryDTO = $this->categoryRepository->getById($categoryId);
        $updateName = $_POST['updateName'] ?? $categoryDTO->name;

        if (!$categoryDTO instanceof CategoryDataTransferObject) {
            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }

        if (isset($_POST['updateCategory'])) {
            if (trim($updateName) === '') {
                $errors['categoryDTO'] = 'Product Name musst be given';
            } else {
                $categoryDTO->name = trim($updateName);
                $this->categoryEntityManager->update($categoryDTO);
                $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryDTO->id);

                return;
            }
        }

        if (isset($_POST['deleteCategory'])) {
            $this->categoryEntityManager->delete($categoryDTO->id);

            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }

        if (isset($_POST['createProduct'])) {
            $create = $_POST['create'];
            $createName = $create['name'] ?? '';
            $createPrice = 0.00;
            if(isset($create['price'])) {
                $createPrice = (float)$create['price'];
            }
            $createDescription = $create['description'] ?? 'NULL';

            if ($createName === '') {
                $errors['product'] = 'Product Name musst be given';
            } else {
                $productDTO = $this->productMapper->map(['name' => trim($createName), 'price' => $createPrice, 'description' => $createDescription, 'categoryId' => $categoryId]);
                $this->productEntityManager->insert($productDTO);
            }
        }

        if (isset($_POST['addProduct'], $_POST['selectProduct'])) {
            $productId = (int)$_POST['selectProduct'];
            $this->categoryProductEntityManager->insert($categoryDTO->id, $productId);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $categoryDTO->id);

            return;
        }

        if(!empty($errors)){
            $this->viewInterface->addTlpParam('error', $errors);
        }

        $this->viewInterface->addTlpParam('categoryDTO', $categoryDTO);
        $this->viewInterface->addTlpParam('productDTOList', $this->productRepository->getList());
        $this->viewInterface->addTlpParam('productDTOListExcludeCategory', $this->productRepository->getListExcludeCategory());
        $this->viewInterface->addTlpParam('updateName', $updateName);
        $this->viewInterface->addTlpParam('createProduct', $create);
        $this->viewInterface->addTemplate('backend/categoryDetail.tpl');
    }
}
