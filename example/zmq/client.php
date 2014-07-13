<?php
$dsn = "tcp://127.0.0.1:5555";
$socket = new ZMQSocket(new ZMQContext(), ZMQ::SOCKET_REQ, 'jsonrpc');
$endpoints = $socket->getEndpoints();
printf("Connecting to %s\n", $dsn);
$socket->connect($dsn);
$socket->send(json_encode(['jsonrpc' => '2.0', 'method' => 'greet', 'params' => ['world'], 'id' => 1]));
printf("Server said: %s\n", $socket->recv());
