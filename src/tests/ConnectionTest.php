<?php

use PHPUnit\Framework\TestCase;
use Ssh\Connection;

final class ConnectionTest extends TestCase
{
    public function testCreate()
    {
        $con = new Connection('127.0.0.1');
        $this->assertEquals($con, true);
    }
}
