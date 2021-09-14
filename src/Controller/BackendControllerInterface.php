<?php
declare(strict_types=1);

namespace App\Controller;

interface BackendControllerInterface{
    public function actionShow(): void;
    public function actionEdit(): void;
}