<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Container;
use App\Core\View\ViewInterface;
use App\Controller\ControllerInterface;
use App\Model\Repository\CategoryRepository;

final class Category implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private array $categoryDTOList;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $categoryRepository = $container->get(CategoryRepository::class);
        $this->categoryDTOList = $categoryRepository->getList();
    }

    public function action(): void
    {
        $this->viewInterface->addTlpParam('categoryDTOList', $this->categoryDTOList);
        $this->viewInterface->addTemplate('category.tpl');
    }
}