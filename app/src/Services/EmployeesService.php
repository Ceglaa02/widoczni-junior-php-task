<?php

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class EmployeesService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getEmployees(): array
    {
        try {
            $queryEmployees = $this->connection->createQueryBuilder();
            $queryEmployees
                ->select('*')
                ->from('employees');

            return $queryEmployees->executeQuery()->fetchAllAssociative();
        } catch (Exception $e) {
            return [];
        }
    }
}