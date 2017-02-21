<?php

namespace Database\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Ssh\Connection;

class SyncDatabaseCommand extends Command
{
    protected function configure()
    {
        $this->setName('database:sync-db')
          ->setDescription('Synchronizes one of your databases with a
              remote database by the same name.')
          ->setHelp('Help')
          ->addArgument('database', InputArgument::REQUIRED, 'The database name.');
    }
}
