<?php

namespace app;

class SocketHttpServer
{
    private $socket;
    private string $host;
    private int $port;

    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function start(): void
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($this->socket, $this->host, $this->port );
        socket_listen($this->socket);

        while ($connect = socket_accept($this->socket)) {
            $request = socket_read($connect, 1024);
            echo "$request\n\n" ;

            $response = $this->processHttpRequest($request);

            socket_write($connect, $response);
        }

        socket_close($connect);
    }

    private function processHttpRequest(string $request): string
    {
        $requestLines = explode(' ', $request);
        $methodHttp = $requestLines[0];

        $response = match ($methodHttp) {
            'GET' => "200 OK\r\n\r\nHello, Client! You send GET request",
            'POST' => "200 OK\r\n\r\nHello, Client! You send POST request",
            default => "400 Bad Request"
        };

        return "HTTP/1.1 " . $response;
    }

    public function __destruct() {
        if (isset($this->socket)) {
            socket_close($this->socket);
        }
    }
}