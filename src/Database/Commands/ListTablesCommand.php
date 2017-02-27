<?php
namespace Database\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Database\Commands\UrbanScotchDbCommand;
use Ssh\Connection;


class ListTablesCommand extends UrbanScotchDbCommand
{

    protected function configure()
    {
      $this->setName('database:list-t')
        ->setDescription('List all the tables for the desired remote database.')
        ->setHelp('Help')
        ->addArgument('database', InputArgument::REQUIRED, 'The database name.')
        ->addOption('both', 'b', InputOption::VALUE_NONE, 'Display both remote and local tables.')
        ->addOption('local', 'l', InputOption::VALUE_NONE, 'Display only local tables.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');
        $output->writeln("TABLES:");

        //Ssh to server.
        $this->connectToServer();
        if($input->getOption('both')) {
            $remoteTables = $this->getTableList();

        } else {
            $results = $this->getTableList();
        }

        $output->writeln($results);
    }

    protected function getTableList()
    {
        $username = getenv('REMOTE_USERNAME');
        $password = getenv('REMOTE_PASSWORD');
        $cmd = "mysql -u $username -p$password -e 'SHOW TABLES' $this->database";
        return $this->connection->execute($cmd);
    }
}
