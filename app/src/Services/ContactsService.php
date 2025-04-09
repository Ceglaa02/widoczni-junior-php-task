<?php

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ContactsService
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getContacts(): array
    {
        try {
            $queryContacts = $this->connection->createQueryBuilder();
            $queryContacts
                ->select('*')
                ->from('contacts');

            return $queryContacts->executeQuery()->fetchAllAssociative();
        } catch (Exception $e) {
            return [];
        }
    }
}