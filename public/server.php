<?php

use app\SocketServer;

require_once("../vendor/autoload.php");

$host = "127.0.0.1";
$port = 8080;

$socketServer = new SocketServer($host, $port);
$socketServer->start();
