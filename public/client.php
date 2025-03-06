<?php

use app\SocketServerClient;

require_once("../vendor/autoload.php");

$host = "127.0.0.1";
$port = 8080;

$socketServerClient = new SocketServerClient($host, $port);

$socketServerClient->connect();
$socketServerClient->sendMessage("hello from client");

