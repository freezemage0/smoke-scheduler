<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


require_once __DIR__ . '/vendor/autoload.php';


$socket = \Freezemage\Smoke\Socket\Socket::create(AF_UNIX, SOCK_STREAM, 0);
$socket->bind('smoke-scheduler-cli')->connect('smoke-scheduler-server');
$socket->setOption(SO_REUSEADDR, true);
$socket->setBlocking(false);
$socket->write('start 5');

do {
    $result = $socket->read(1024);
} while (empty($result));

echo $result . PHP_EOL;
$socket->close();

exit(0);