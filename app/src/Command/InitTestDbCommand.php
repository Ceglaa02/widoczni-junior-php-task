<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:init-db',
    description: 'Wstawia testowe dane do bazy danych',
)]
class InitTestDbCommand extends Command
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    private function generateInsertQuery($table, $columns, $data): string
    {
        $values = [];
        foreach ($data as $row) {
            $values[] = '(' . implode(', ', array_map(fn($val) => "'" . addslashes($val) . "'", $row)) . ')';
        }

        $query = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ') VALUES ' . implode(', ', $values);
        return $query;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $faker = Factory::create();

            $packages = [];
            for ($i = 0; $i < 10; $i++) {
                $packages[] = [
                    'name' => $faker->word,
                    'price' => $faker->randomFloat(2, 50, 200)
                ];
            }

            $this->connection->executeQuery($this->generateInsertQuery('packages', ['name', 'price'], $packages));

            $packagesIds = $this->connection->fetchFirstColumn('SELECT id FROM packages');

            $clients = [];
            for ($i = 0; $i < 10; $i++) {
                $clients[] = [
                    'name' => $faker->company,
                    'nip' => $faker->numerify('PL##########'),
                    'package_id' => $faker->randomElement($packagesIds)
                ];
            }

            $this->connection->executeQuery($this->generateInsertQuery('clients', ['name', 'nip', 'package_id'], $clients));

            $clientIds = $this->connection->fetchFirstColumn('SELECT id FROM clients');

            $employees = [];
            for ($i = 0; $i < 10; $i++) {
                $employees[] = [
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'client_id' => $faker->randomElement($clientIds),
                ];
            }

            $this->connection->executeQuery($this->generateInsertQuery('employees', ['name', 'email', 'client_id'], $employees));

            $contacts = [];
            for ($i = 0; $i < 10; $i++) {
                $contacts[] = [
                    'client_id' => $faker->randomElement($clientIds),
                    'name' => $faker->name,
                    'email' => $faker->email,
                    'phone' => $faker->phoneNumber,
                ];
            }

            $this->connection->executeQuery($this->generateInsertQuery('contacts', ['client_id', 'name', 'email', 'phone'], $contacts));


            $output->writeln('<info>Dane zostały poprawnie wstawione.</info>');

            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}