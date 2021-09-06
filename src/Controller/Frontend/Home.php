<?php
declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Core\Redirect;
use App\Core\ViewInterface;
use App\Model\CategoryRepository;
use App\Controller\ControllerInterface;

final class Home implements ControllerInterface
{
    private ViewInterface $smartyController;
    private array $categoryList;

    public function __construct(ViewInterface $smartyController, CategoryRepository $categoryRepository, Redirect $redirect)
    {
        $this->smartyController = $smartyController;
        $this->categoryList = $categoryRepository->getList();
    }

    public function action(): void
    {
        $this->smartyController->addTlpParam('categoryList', $this->categoryList);
        $this->smartyController->addTemplate('home.tpl');
    }
}