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
    private $connection;
    private $database;
    private $table;

    protected function configure()
    {
      $this->setName('database:sync-table')
        ->setDescription('Synchronizes one of your database tables with a
            remote database table by the same name.')
        ->setHelp('Help')
        ->addArgument('database', InputArgument::REQUIRED, 'The database name.')
        ->addArgument('table', InputArgument::REQUIRED, 'The database table name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');
        $this->table = $input->getArgument('table');

        $output->writeln('Synchronizing '
            . $databaseName
            . "."
            . $tableName
        );

        $this->connectToServer();

        // Close ssh connection.
        unset($this->connection);
    }

    /**
     * Attempts to connect to remote server.
     * @return Ssh/Connection
     */
    protected function connectToServer()
    {
        $host = getenv('SERVER_HOST');
        $username = getenv('SERVER_USERNAME');
        $password = getenv('SERVER_PASSWORD');

        try {
            $this->connection = new Connection($host);
            $this->connection->authenticate($username, $password);
        } catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @return array
     */
    protected getRemoteTable()
    {
        $file = $this->dumpTable();
        $this->fetchTable($file);
    }

    /**
     * @return string
     */
    protected function dumpTable()
    {
        $time = time();
        $username = getenv('REMOTE_USERNAME');
        $password = getenv('REMOTE_PASSWORD');
        $filename = "{$this->database}_{$this->table}_$time.sql";
        $cmd = "mysqldump -u $username -p$password
            $this->database $this->table > $filename";

        return isset($this->connection->execute($cmd)) ? $filename : false;
    }

    /**
     * @param string file database table file
     * @return string
     */
    protected function fetchTable($file)
    {

        /**
         * @todo Duplicate code - Refactor
         */
        $host = getenv('SERVER_HOST');
        $username = getenv('SERVER_USERNAME');
        $password = getenv('SERVER_PASSWORD');
        $ftpConnection = ftp_connect($host);

        if(ftp_login($ftpConnection, $username, $password)) {
            ftp_get($ftpConnection, $file);
        }
    }
}
