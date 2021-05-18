<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Actor;


use Freezemage\Smoke\Socket\Socket;


interface ActorInterface {
    public function process(Socket $socket): ?string;
}