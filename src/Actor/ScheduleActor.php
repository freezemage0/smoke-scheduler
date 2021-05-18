<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Actor;

use Freezemage\Smoke\ScheduleObserver;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\Socket;


class ScheduleActor implements ActorInterface {
    protected $scheduler;

    public function __construct(Scheduler $scheduler) {
        $this->scheduler = $scheduler;
    }

    public function process(Socket $socket): ?string {
        $observer = new ScheduleObserver($socket);
        $this->scheduler->subscribe($observer);

        return null;
    }
}