<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Cache\Adapter\PdoAdapter;

class AbstractApiTestCase extends WebTestCase
{
    protected ?KernelBrowser $client;

    protected EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
        foreach ($this->loadFixtures() as $fixture) {
            assert($fixture instanceof Fixture);
            $fixture->load($this->entityManager);
        }
    }

    protected function loadFixtures(): array
    {
        return [];
    }

    protected function tearDown(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeStatement('SET foreign_key_checks = 0');
        foreach ($this->truncateEntities() as $entityClass) {
            $tableName = $this->entityManager->getClassMetadata($entityClass)->getTableName();
            $connection->executeStatement($platform->getTruncateTableSQL($tableName, true));
        }
        $connection->executeStatement('SET foreign_key_checks = 1');
        $this->entityManager->clear();
    }

    protected function truncateEntities(): array
    {
        return [];
    }
}
