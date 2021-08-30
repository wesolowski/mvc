<?php
declare(strict_types=1);

namespace AppTest\Controller;

use App\Controller\Home;
use App\Model\ProductRepository;
use PHPUnit\Framework\TestCase;

class HomeTest //extends TestCase
{
    public function testAction(){
        $home = new Home(new \Smarty(), new ProductRepository());
    }
}