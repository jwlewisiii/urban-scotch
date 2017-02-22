<?php

namespace Database\Commands;

use Database\Commands\UrbanScotchDbCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class SyncDatabaseCommand extends UrbanScotchDbCommand
{

    protected function configure()
    {
        $this->setName('database:sync-db')
          ->setDescription('Synchronizes one of your databases with a
              remote database by the same name.')
          ->setHelp('Help')
          ->addArgument('database', InputArgument::REQUIRED, 'The database name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');
        $output->writeln('Synchronizing ' . $this->database);

        $this->connectToServer();
        $file = $this->getRemoteDatabase();
        $output->writeln("DOWNLOADED: $file");
        $this->updateDatabase($file);
        $output->writeln("$this->database now updated.");
        // Close ssh connection.
        unset($this->connection);
    }

    /**
     * Copies the database SQL file to local machine.
     * @todo clean up file on remote machine.
     */
    protected function getRemoteDatabase()
    {
        $file = $this->dumpDatabase();
        $this->connection->secureCopy()->requestFile($file);
        return $file;
    }

    /**
     * Dumps mysql database into a SQL file.
     * @return string | bool
     */
     protected function dumpDatabase()
     {
         $time = time();
         $username = getenv('REMOTE_USERNAME');
         $password = getenv('REMOTE_PASSWORD');
         $filename = "{$this->database}_$time.sql";
         $cmd = "mysqldump -u $username -p$password $this->database > $filename";

         return ($this->connection->execute($cmd) !== false) ? $filename : false;
     }

     protected function updateDatabase($file)
     {
         $username = getenv('LOCAL_USERNAME');
         $password = getenv('LOCAL_PASSWORD');
         $cmd = "mysql $this->database -u $username -p$password < $file";
         exec($cmd);
         unlink($file);
     }
}
