<?php
declare(strict_types=1);

namespace App\Controller\Backend;

interface BackendControllerInterface{
    public function action(): void;
}
