<?php

namespace Database\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class SyncTableCommand extends Command
{
    protected function configure()
    {
      $this->setName('database:sync-table')
        ->setDescription('Synchronizes one of your database tables with a
            remote database table by the same name.')
        ->setHelp('Help')
        ->addArgument('table-name', InputArgument::REQUIRED, 'The database table name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Synchronizing ' . $input->getArgument('table-name'));
    }
}
