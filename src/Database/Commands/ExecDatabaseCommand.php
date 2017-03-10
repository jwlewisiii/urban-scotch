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
          ->addOption('local', 'l', InputOption::VALUE_NONE, 'Executes query on local database.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');
        if($input->getOption('both')) {

        }


    }

}
