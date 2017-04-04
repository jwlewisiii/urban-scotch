<?php

namespace Database\Commands;

use Database\Commands\UrbanScotchDbCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SyncTableCommand extends UrbanScotchDbCommand
{

    protected function configure()
    {
      $this->setName('database:sync-t')
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
     * @return array
     */
    protected function getRemoteTable()
    {
        $file = $this->dumpTable();
        $this->connection->secureCopy()->requestFile($file);
        $this->connection->execute("rm $file");
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
        $cmd = "mysqldump --single-transaction -u $username -p$password $this->database $this->table > $filename";

        return ($this->connection->execute($cmd) !== false) ? $filename : false;
    }

    protected function updateTable($file)
    {
        $username = getenv('LOCAL_USERNAME');
        $password = getenv('LOCAL_PASSWORD');
        $cmd = "mysql $this->database -u $username -p$password < $file";
        exec($cmd);
        unlink($file);
    }
}
