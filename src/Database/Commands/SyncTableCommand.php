<?php

namespace Database\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Ssh\Connection;


class SyncTableCommand extends Command
{
    protected function configure()
    {
      $this->setName('database:sync-table')
        ->setDescription('Synchronizes one of your database tables with a
            remote database table by the same name.')
        ->setHelp('Help')
        ->addArgument('database', InputArgument::REQUIRED, 'The database name.')
        ->addArgument('table-name', InputArgument::REQUIRED, 'The database table name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Synchronizing '
            . $input->getArgument('database')
            . "."
            . $input->getArgument('table-name')
        );

        $connection = $this->connectToServer();
    }

    /**
     * Attempts to connect to remote server.
     * @return Ssh/Connection
     */
    private function connectToServer()
    {
        $host = getenv('SERVER_HOST');
        $username = getenv('SERVER_USERNAME');
        $password = getenv('SERVER_PASSWORD');

        try {
            $connection = new Connection($host);
            $connection->authenticate($username, $password);
        } catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return $connection;
    }
}
