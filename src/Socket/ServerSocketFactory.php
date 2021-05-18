<?php


namespace Freezemage\Smoke\Socket;


class ServerSocketFactory {
    protected $address;
    protected $port;

    public function createTcp(string $address, int $port = 0): Socket {
        return Socket::create(AF_INET, SOCK_STREAM, SOL_TCP)
            ->bind($address, $port)
            ->setOption(SO_REUSEADDR, true)
            ->setBlocking(false)
            ->listen();
    }

    public function createUnix(string $address): Socket {
        return Socket::create(AF_UNIX, SOCK_STREAM, 0)
            ->bind($address)
            ->setOption(SO_REUSEADDR, true)
            ->setBlocking(false)
            ->listen();
    }
}