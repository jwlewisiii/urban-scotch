<?php

namespace Database\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Ssh\Connection;

abstract class UrbanScotchDbCommand extends Command
{
    protected $connection;
    protected $database;
    protected $table;

    /**
     * Attempts to connect to remote server and authenticate.
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

}
