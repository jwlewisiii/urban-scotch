<?php
namespace Database\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Ssh\Connection;


class ListTablesCommand extends Command
{
    private $connection;
    private $database;

    protected function configure()
    {
      $this->setName('database:list-t')
        ->setDescription('List all the tables for the desired remote database.')
        ->setHelp('Help')
        ->addArgument('database', InputArgument::REQUIRED, 'The database name.')
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');

        //Ssh to server.
    }
