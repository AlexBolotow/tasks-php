<?php

namespace app;

class SocketServerClient
{
    private $socket;
    private $host;
    private $port;
    public function __construct($host, $port) {
        $this->host = $host;
        $this->port = $port;
    }

    public function connect(): void {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($this->socket, $this->host, $this->port);
    }

    public function sendMessage($message): void{
        socket_write($this->socket, $message);
        $response = socket_read($this->socket, 1024);
        echo $response;
    }

    public function __destruct() {
        if (isset($this->socket)) {
            socket_close($this->socket);
        }
    }
}
