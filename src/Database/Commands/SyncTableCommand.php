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
            . $this->database
            . "."
            . $this->table
        );

        $this->connectToServer();
        $file = $this->getRemoteTable();
        $output->writeln("DOWNLOADED: $file");
        $this->updateTable($file);
        $output->writeln("$this->table now updated.");
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
    protected function getRemoteTable()
    {
        $file = $this->dumpTable();
        $this->scpRemoteFile($file);
        return $file;
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
        $cmd = "mysqldump -u $username -p$password $this->database $this->table > $filename";

        return ($this->connection->execute($cmd) !== false) ? $filename : false;
    }

    protected function updateTable($file)
    {
        $username = getenv('LOCAL_USERNAME');
        $password = getenv('LOCAL_PASSWORD');
        $cmd = "mysql concierge -u $username -p$password < $file";
        exec($cmd);
        unlink($file);
    }

    /**
     * Uses an FTP get to grab a file on a remote server.
     * @todo move all ftp funcitons into a ftp object.
     * @param string file database table file
     * @return string
     */
    protected function ftpGetTable($file)
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

    /**
     * Uses SCP to grab a file from a remote server.
     * @todo move all scp funcitons into a scp object.
     * @param string file sql table file.
     * @return string
     */
     public function scpRemoteFile($file)
     {
         /**
          * @todo remove file after moved to local server.
          */
         return ssh2_scp_recv($this->connection->getResource(),$file,$file);
     }
}
