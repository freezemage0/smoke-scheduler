<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Socket;


use Freezemage\Smoke\Actor\ActorInterface;


class Listener {
    protected $socket;
    protected $actor;

    public function __construct(Socket $socket, ActorInterface $actor) {
        $this->socket = $socket;
        $this->actor = $actor;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $response = $this->actor->process($client);

        if (!empty($response)) {
            $client->write($response);
        }
    }
}