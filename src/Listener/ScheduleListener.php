<?php


namespace Freezemage\Smoke\Listener;


use Freezemage\Smoke\ScheduleObserver;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class ScheduleListener implements ListenerInterface {
    protected $socket;
    protected $scheduler;

    public function __construct(Socket $socket, Scheduler $scheduler) {
        $this->socket = $socket;
        $this->scheduler = $scheduler;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $data = $client->read(1024);

        if ($data == null) {
            return;
        }

        $subscriber = new ScheduleObserver($this->socket);
        $this->scheduler->subscribe($subscriber);
    }
}