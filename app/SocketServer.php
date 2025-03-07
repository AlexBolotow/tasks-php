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

        if ($connect = socket_accept($this->socket)) {
            if ($this->acceptTextFile($connect)) {
                socket_write($connect, 'File accepted successful');
            } else {
                socket_write($connect, 'File not accepted');
            }
        }

        //socket_close($this->socket);
    }

    private function acceptTextFile(\Socket $connect): bool {
        socket_write($connect, "Hello from server. In first message send name of file (****.txt).
        Then send file content. End of work - message contains only empty line\n");

        $fileName = socket_read($connect, 1024);
        if (!str_ends_with($fileName, '.txt')) {
            socket_write($connect, "Wrong file name\n");
            return false;
        } else {
            socket_write($connect, "File created\n");
        }

        $path = '/home/alex/PhpstormProjects/tasks-php/resources/' . $fileName;
        $file = fopen($path, 'w');
        $message = socket_read($connect, 1024);

        while (!empty($message)) {
            //echo '1';
            fwrite($file, $message);
            socket_write($connect, "Message accepted\n");
            $message = socket_read($connect, 1024);
        }

        fclose($file);

        return true;
    }

    public function __destruct() {
        if (isset($this->socket)) {
            socket_close($this->socket);
        }
    }
}
