<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Socket\Socket;


final class Listener implements ListenerInterface {
    private $socket;

    public function __construct(Socket $socket) {
        $this->socket = $socket;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

}