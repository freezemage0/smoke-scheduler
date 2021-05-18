<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;

use Freezemage\Smoke\Socket\Socket;


class ScheduleObserver {
    protected $socket;

    public function __construct(Socket $socket) {
        $this->socket = $socket;
    }

    public function notify(Scheduler $scheduler): void {
        if ($scheduler->isRunning() && $scheduler->timeLeft() <= 0) {
            $this->socket->write('Time is up.');
        }
    }
}