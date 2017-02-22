<?php

namespace Tests;

use Ssh\Connection;
Use Tests\UrbanScotchTestCase;

class ConnectionTest extends UrbanScotchTestCase
{
    public function testConnection()
    {
        $con = new Connection($this->serverHost);
        $this->assertNotFalse($con->getResource());
    }

    public function testAuthentication()
    {
        $con = new Connection($this->serverHost);
        $results = $con->authenticate(
            $this->serverUsername,
            $this->serverPassword
        );
        $this->assertTrue($results);
    }

    public function testBadAuthentication()
    {
        $con = new Connection($this->serverHost);
        $results = $con->authenticate(
            'username',
            'password'
        );
        $this->assertFalse($results);
    }
}
