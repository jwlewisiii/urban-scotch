<?php

namespace Ssh\Protocols;

class SecureCopy {

    private $connection;

    function __construct($connection)
    {
        $this->connection = $connection;
    }

    /**
     * Copies a file from a remote server.
     * @param string remoteFile
     * @param string localFile
     * @return bool
     */
    public function requestFile($remoteFile, $localFile = null)
    {
        $localFile = isset($localFile) ? $localFile : $remoteFile ;
        return ssh2_scp_recv($this->connection,$remoteFile,$localFile);
    }

    public function sendFile($resource, $localFile, $remoteFile = null)
    {
        $remoteFile = isset($remoteFile) ? $remoteFile : $localFile;
        return ssh2_scp_send($this->connection, $localFile, $remoteFile);
    }
}
