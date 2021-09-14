<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Core\Container;
use App\Core\View\ViewInterface;

class Home implements BackendControllerInterface
{
    private ViewInterface $viewInterface;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
    }

    public function action(): void
    {
        $footerLink = ['link' => '?area=Admin&page=Logout', 'name' => 'Admin - Logout'];
        $this->viewInterface->addTlpParam('footerLink', $footerLink);
        $this->viewInterface->addTlpParam('username', $_SESSION['user']['username']);
        $this->viewInterface->addTemplate('backend/home.tpl');
    }
}