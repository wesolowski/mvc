<?php
declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Doctrine
{
    private bool $isDevMode = true;
    private ?string $proxyDir = null;
    private ?string $cache = null;
    private bool $useSimpleAnnotationReader = false;
    private array $settings;

    private Configuration $config;
    private EntityManager $entityManager;

    public function __construct(array $settings = [])
    {
        $this->settings['dbname'] = $settings['dbname'] ?? 'mvc';
        $this->settings['user'] = $settings['user'] ?? 'philipp';
        $this->settings['password'] = $settings['password'] ?? 'nexusval';
        $this->settings['host'] = $settings['host'] ?? 'localhost';
        $this->settings['charset'] = $settings['charset'] ?? 'utf8mb4';
        $this->settings['driver'] = $settings['driver'] ?? 'pdo_sqlite';

        $this->config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../../src"), $this->isDevMode, $this->proxyDir, $this->cache, $this->useSimpleAnnotationReader);

        try {
            $this->entityManager = EntityManager::create($this->settings, $this->config);
        } catch (\Doctrine\ORM\ORMException $e) {
        }
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }

    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }
}