<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class UrbanScotchTestCase extends TestCase {

    protected $serverHost;
    protected $serverUsername;
    protected $serverPassword;

    public function setUp()
    {
        $dotenv = new Dotenv("/home/dev/urban-scotch/");
        $dotenv->load();

        $this->serverHost = getenv('SERVER_HOST');
        $this->serverUsername = getenv('SERVER_USERNAME');
        $this->serverPassword = getenv('SERVER_PASSWORD');
    }

}
