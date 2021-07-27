<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Listener;


use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class WelcomeListener implements ListenerInterface {
    protected $socket;

    public function __construct($socket) {
        $this->socket = $socket;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $client->write('Welcome to Smoke Scheduler v0.4.0');
    }
}