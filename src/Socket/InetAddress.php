<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Socket;


class InetAddress {
    protected $address;
    protected $port;

    public function __construct(string $address, int $port = 0) {
        $this->address = $address;
        $this->port = $port;
    }

    public function getAddress(): string {
        return $this->address;
    }

    public function getPort(): int {
        return $this->port;
    }
}