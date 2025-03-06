<?php

namespace app;

class SocketServer
{
    private $socket;
    private string $host;
    private int $port;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function start(): void
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($this->socket, $this->host, $this->port );
        socket_listen($this->socket);

        $connect = socket_accept($this->socket);
        $message = socket_read($connect, 1024);
        echo $message;

        socket_write($connect, "message received by server");
    }

    public function __destruct() {
        if (isset($this->socket)) {
            socket_close($this->socket);
        }
    }
}
