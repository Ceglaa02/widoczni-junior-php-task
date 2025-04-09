<?php

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ClientsService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getClients(): array
    {
        try {
            $queryClients = $this->connection->createQueryBuilder();
            $queryClients
                ->select('*')
                ->from('clients');

            return $queryClients->executeQuery()->fetchAllAssociative();
        } catch (Exception $e) {
            return [];
        }
    }
}