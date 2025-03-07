<?php

use app\SocketServerClient;

require_once("../vendor/autoload.php");

$host = "127.0.0.1";
$port = 8080;

//TODO попробовать параллельную работу
for ($i = 0; $i < 3; $i++) {

    $socketServerClient = new SocketServerClient($host, $port);

    $socketServerClient->connect();

    $filename = 'client.txt';

    $socketServerClient->sendMessage($filename);
    $socketServerClient->sendMessage("hello from client\n");
    $socketServerClient->sendMessage("its my first message\n");
    $socketServerClient->sendMessage("its my second message\n");
    $socketServerClient->sendMessage("its my third message\n");
    $socketServerClient->sendMessage("its my fourth message\n");
    $socketServerClient->sendMessage("");
}
