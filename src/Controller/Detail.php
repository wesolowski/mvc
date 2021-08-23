<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;

final class Detail implements ControllerInterface
{
    private ViewInterface $smartyController;
    public function __construct(ViewInterface $smartyController)
    {
        $this->smartyController = $smartyController;
    }

    public function action(ProductRepository $pr): void
    {
        $id = $_GET['id'];
        $output = $pr->getProduct($id);
        $this->smartyController->addTlpParam('link', "../../index.php?page=Home");
        $this->smartyController->addTlpParam('content', $output);
        $this->smartyController->addTemplate('detail.tpl');
    }
}