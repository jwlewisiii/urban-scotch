<?php

namespace Ssh;

class Connection {

  private $connection;
  private $host;
  private $username;
  private $password;
  private $post;
  private $isAuthenticated;

  function __construct($host, $port = 22)
  {
    $this->host = $host;
    $this->port = $port;
    $this->connection = ssh2_connect($this->host, $this->port);
  }

  public function getResource()
  {
      return $this->connection;
  }

  public function authenticate($username, $password)
  {
    $this->username = $username;
    $this->password = $password;

    $this->isAuthenticated = ssh2_auth_password(
      $this->connection,
      $this->username,
      $this->password
    );

    if(!$this->isAuthenticated)
        throw new Exception("Unable to authenticate user, $username.");

    return $this->isAuthenticated;
  }

  public function execute($command)
  {
    $stream = ssh2_exec($this->connection, $command);
    stream_set_blocking($stream, true);
    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
    return stream_get_contents($stream_out);
  }

}
