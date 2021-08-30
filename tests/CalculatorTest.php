<?php declare(strict_types=1);

namespace AppTest;

use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        $calculator = new \App\Calculator();

        self::assertSame(25, $calculator->add([5, 20]));
    }
}