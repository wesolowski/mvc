<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\View\ViewInterface;

class Logout implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private string $username;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->username = $_SESSION['user']['username'] ?? '';
        if (isset($_SESSION['user'])) {
            $_SESSION = [];
        }
    }

    public function action(): void
    {
        $footerLink = ['link' => ' ', 'name' => 'Public - Category'];
        $this->viewInterface->addTlpParam('username', $this->username);
        $this->viewInterface->addTlpParam('footerLink', $footerLink);
        $this->viewInterface->addTemplate('backend/logout.tpl');
    }
}