<?php

use app\SocketHttpServer;

require_once("../vendor/autoload.php");

$host = "127.0.0.1";
$port = 8080;

$socketServer = new SocketHttpServer($host, $port);
$socketServer->start();