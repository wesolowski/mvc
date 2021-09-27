<?php
declare(strict_types=1);

namespace App\Controller\Backend;

use App\Controller\ControllerInterface;
use App\Core\Container;
use App\Core\Redirect\RedirectInterface;
use App\Core\UserValidation;
use App\Core\View\ViewInterface;

class Login implements ControllerInterface
{
    private ViewInterface $viewInterface;
    private RedirectInterface $redirect;
    private UserValidation $userValidation;

    public function __construct(Container $container)
    {
        $this->viewInterface = $container->get(ViewInterface::class);
        $this->redirect = $container->get(RedirectInterface::class);
        $this->userValidation = $container->get(UserValidation::class);
    }

    public function action(): void
    {
        if (isset($_POST['login'])) {
            $errors = $this->userValidation->validation(['name' => $_POST['name'], 'password' => $_POST['password']]);

            if (!empty($errors)) {
                $this->viewInterface->addTlpParam('name', $_POST['name']);
                $this->viewInterface->addTlpParam('errors', $errors);
            } else {
                $_SESSION['user'] = ['name' => $_POST['name'], 'password' => $_POST['password']];
                $this->redirect->redirect('index.php?area=Admin&page=Home');
            }
        }
        $footerLink = ['link' => ' ', 'name' => 'Public - Category'];
        $this->viewInterface->addTlpParam('footerLink', $footerLink);
        $this->viewInterface->addTemplate('backend/login.tpl');
    }
}