<?php

namespace Database\Commands;

use Database\Commands\UrbanScotchDbCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ExecDatabaseCommand extends UrbanScotchDbCommand
{

    public function configure()
    {
        $this->setName('database:exec')
          ->setDescription('Executes a SQL query on a remote database and displays
                the results')
          ->setHelp('Help')
          ->addArgument('database', InputArgument::REQUIRED, 'The database name.')
          ->addArgument('sqlCommand', InputArgument::REQUIRED, 'SQL command to execute on database.')
          ->addOption('local', 'l', InputOption::VALUE_NONE, 'Executes query on local database.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');
        $this->sqlCommand = $input->getArgument('sqlCommand');
        $this->connectToServer();
        if($input->getOption('local')) {
            $cmd = $this->buildCommand($this->sqlCommand, true);
            $output->writeln("Local Results: \n");
            $results = system($cmd);
        } else {
            $cmd = $this->buildCommand($this->sqlCommand);
            $results = $this->connection->execute($cmd);
            $output->writeln("Remote Results: \n");
            $output->writeln($results);
        }
    }

    private function buildCommand($sqlCommand, $isLocal = false)
    {
        if($isLocal) {
            $username = getenv('LOCAL_USERNAME');
            $password = getenv('LOCAL_PASSWORD');
        } else {
            $username = getenv('REMOTE_USERNAME');
            $password = getenv('REMOTE_PASSWORD');
        }

        $cmd = "mysql -u $username -p$password {$this->database} -e '$sqlCommand'";
        return $cmd;
    }




}
