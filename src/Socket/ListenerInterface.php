<?php


namespace Freezemage\Smoke\Socket;


interface ListenerInterface {
    /**
     * This is wrong.
     * Listener should not contain socket reference, it should be transferred to "handle" method by Server class.
     * TODO: refactor SRP violation
     *
     * @return Socket
     */
    public function getSocket(): Socket;

    public function handle(Socket $client): void;
}