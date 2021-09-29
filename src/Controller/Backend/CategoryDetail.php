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
    private ?CategoryDataTransferObject $categoryDTO;
    private array $errors = [];
    private array $create = [];
    private string $updateName;
    private int $categoryId;

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

    private function updateProduct(): void
    {
        if (trim($this->updateName) === '') {
            $this->errors['categoryDTO'] = 'Product Name musst be given';
        } else {
            $this->categoryDTO->name = trim($this->updateName);
            $this->categoryEntityManager->update($this->categoryDTO);
            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $this->categoryDTO->id);
        }
    }

    private function createProduct(): void
    {
        $this->create = $_POST['create'];
        $createName = $this->create['name'] ?? '';
        $createPrice = $this->create['price'] ?? 0.00;
        $createDescription = $this->create['description'] ?? null;

        if ($createName === '') {
            $this->errors['product'] = 'Product Name musst be given';
        } else {
            $productDTO = $this->productMapper->map(['name' => trim($createName), 'price' => $createPrice, 'description' => $createDescription, 'categoryId' => $this->categoryId]);
            $this->productEntityManager->insert($productDTO);
        }
    }

    private function submitPressed(): void
    {
        $this->updateName = $_POST['updateName'] ?? $this->categoryDTO->name;

        if (isset($_POST['updateCategory'])) {
            $this->updateProduct();
            return;
        }

        if (isset($_POST['deleteCategory'])) {
            $this->categoryEntityManager->delete($this->categoryDTO->id);

            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }

        if (isset($_POST['createProduct'])) {
            $this->createProduct();
        }

        if (isset($_POST['addProduct'], $_POST['selectProduct'])) {
            $productId = (int)$_POST['selectProduct'];
            $this->categoryProductEntityManager->insert($this->categoryDTO->id, $productId);

            $this->redirect->redirect('index.php?area=Admin&page=CategoryDetail&categoryId=' . $this->categoryDTO->id);
        }
    }

    public function action(): void
    {
        if (!isset($_GET['categoryId']) || $_GET['categoryId'] === '') {
            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }
        $this->categoryId = (int)$_GET['categoryId'];
        $this->categoryDTO = $this->categoryRepository->getById($this->categoryId);

        if (!$this->categoryDTO instanceof CategoryDataTransferObject) {
            $this->redirect->redirect('index.php?area=Admin&page=Category');

            return;
        }

        $this->submitPressed();

        if (!empty($this->errors)) {
            $this->viewInterface->addTlpParam('error', $this->errors);
        }

        $this->viewInterface->addTlpParam('categoryDTO', $this->categoryDTO);
        $this->viewInterface->addTlpParam('productDTOList', $this->productRepository->getList());
        $this->viewInterface->addTlpParam('productDTOListExcludeCategory', $this->productRepository->getListExcludeCategory());
        $this->viewInterface->addTlpParam('updateName', $this->updateName);
        $this->viewInterface->addTlpParam('createProduct', $this->create);
        $this->viewInterface->addTemplate('backend/categoryDetail.tpl');
    }
}
