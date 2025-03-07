<?php

require_once("../vendor/autoload.php");

use app\SocketHttpServerClient;

require_once("../vendor/autoload.php");

$host = "127.0.0.1";
$port = 8080;

$socketServerClient = new SocketHttpServerClient($host, $port);

$socketServerClient->connect();

$socketServerClient->sendHttpRequest("GET /hello HTTP/1.1
Host: localhost
Content-Type: application/json
Content-Length: 0
{}
");