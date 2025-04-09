<?php

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class PackagesService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getPackages(): array
    {
        try {
            $packagesQuery = $this->connection->createQueryBuilder();
            $packagesQuery
                ->select('*')
                ->from('packages');

            return $packagesQuery->executeQuery()->fetchAllAssociative();
        } catch (Exception $e) {
            return [];
        }
    }
}