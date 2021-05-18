<?php


namespace Freezemage\Smoke\Socket;


interface ListenerInterface {
    public function getSocket(): Socket;

    public function handle(Socket $client): void;
}