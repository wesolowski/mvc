<?php
declare(strict_types=1);

namespace AppTest\Model;

use App\Model\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testSettingsDefault(): void
    {
        $database = new Database();
        $getSettings = $database->getSettings();
        self::assertSame('localhost', $getSettings['server']);
        self::assertSame('philipp', $getSettings['user']);
        self::assertSame('nexusval', $getSettings['password']);
        self::assertSame('mvc', $getSettings['database']);
        self::assertSame('utf8mb4', $getSettings['charset']);
    }

    public function testSettings(): void
    {
        $settings = [
            'server' => 'Test',
            'user' => 'Kevin',
            'password' => '',
            'database' => 'lost',
            'charset' => 'utf8'
        ];

        $database = new Database($settings);
        $getSettings = $database->getSettings();

        self::assertSame('Test', $getSettings['server']);
        self::assertSame('Kevin', $getSettings['user']);
        self::assertSame('', $getSettings['password']);
        self::assertSame('lost', $getSettings['database']);
        self::assertSame('utf8', $getSettings['charset']);
    }
}