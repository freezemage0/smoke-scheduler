<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Socket\Socket;


interface ListenerInterface {
    public function getSocket(): Socket;
}