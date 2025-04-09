<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-db',
    description: 'Tworzy tabele w bazie danych do aplikacji agencji i klientów',
)]
class CreateDbCommand extends Command
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        parent::__construct();
        $this->connection = $connection;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $schemaSql = [
            <<<SQL
            CREATE TABLE IF NOT EXISTS packages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL
            );
            SQL,

            <<<SQL
            CREATE TABLE IF NOT EXISTS employees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255)
            );
            SQL,

            <<<SQL
            CREATE TABLE IF NOT EXISTS clients (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                nip VARCHAR(20) NOT NULL,
                package_id INT,
                employee_id INT,
                FOREIGN KEY (package_id) REFERENCES packages(id),
                FOREIGN KEY (employee_id) REFERENCES employees(id)
            );
            SQL,

            <<<SQL
            CREATE TABLE IF NOT EXISTS contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_id INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255),
                phone VARCHAR(50),
                FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
            );
            SQL,
        ];

        foreach ($schemaSql as $sql) {
            $this->connection->executeStatement($sql);
        }

        $output->writeln('<info>Baza danych została zainicjalizowana poprawnie.</info>');

        return Command::SUCCESS;
    }
}