<?php
namespace Database\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Database\Commands\UrbanScotchDbCommand;
use Ssh\Connection;


class ListTablesCommand extends UrbanScotchDbCommand
{

    protected function configure()
    {
      $this->setName('database:list-t')
        ->setDescription('List all the tables for the desired remote database.')
        ->setHelp('Help')
        ->addArgument('database', InputArgument::REQUIRED, 'The database name.')
        ->addOption('both', 'b', InputOption::VALUE_NONE, 'Display both remote and local tables.')
        ->addOption('local', 'l', InputOption::VALUE_NONE, 'Display only local tables.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->database = $input->getArgument('database');
        $this->connectToServer();

        $results = '';
        if($input->getOption('both'))
            $results = $this->getBothList();
        elseif($input->getOption('local'))
            $results = $this->getLocalList();
        else
            $results = $this->getRemoteList();

        $output->writeln($results);
        $output->writeln("----------");
    }

    /**
     * Gets a list of tables from both a local and remote database by the same
     * name.
     * @return string
     */
    protected function getBothList()
    {
        $localList = $this->getLocalList();
        $remoteList = $this->getRemoteList();
        return $localList . "\n\n" . $remoteList;
    }

    /**
     * Gets a list of tables from a remote database.
     * @return string
     */
    protected function getRemoteList()
    {
        $username = getenv('REMOTE_USERNAME');
        $password = getenv('REMOTE_PASSWORD');
        $cmd = "mysql -u $username -p$password -e 'SHOW TABLES' $this->database";
        $results = "REMOTE TABLES: \n---------- \n\n" . $this->connection->execute($cmd);
        return $results;
    }

    /**
     * Gets a list of tables from the local database.
     * @return string
     */
    protected function getLocalList()
    {
        $username = getenv('LOCAL_USERNAME');
        $password = getenv('LOCAL_PASSWORD');
        $cmd = "mysql -u $username -p$password -e 'SHOW TABLES' $this->database";
        echo "LOCAL TABLES: \n---------- \n";
        @exec($cmd, $results);
        return implode("\n", $results);
    }
}
