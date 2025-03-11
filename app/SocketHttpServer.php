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
        socket_bind($this->socket, $this->host, $this->port);
        socket_listen($this->socket);

        while ($connect = socket_accept($this->socket)) {
            $request = socket_read($connect, 1024);
            echo "$request\n\n";

            $response = $this->processHttpRequest($request);

            socket_write($connect, $response);
        }

        socket_close($connect);
    }

    private function processHttpRequest(string $request): string
    {
        $requestLines = explode(' ', $request);
        $methodHttp = $requestLines[0];

        $response = '';
        if ($methodHttp == 'GET') {
            $fp = fsockopen("ssl://www.google.com", 443, $errno, $errstr, 100);

            if (!$fp) {
                echo "$errstr ($errno)<br />\n";
            } else {
                $out = "GET / HTTP/1.1\r\n";
                $out .= "Host: www.google.com\r\n";
                $out .= "Connection: Close\r\n\r\n";

                fwrite($fp, $out);

                while (!feof($fp)) {
                    $response .= fread($fp, 128);
                }

                fclose($fp);
            }
        }

        return $response;
    }

    public function __destruct()
    {
        if (isset($this->socket)) {
            socket_close($this->socket);
        }
    }
}