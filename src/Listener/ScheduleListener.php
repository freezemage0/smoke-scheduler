<?php


namespace Freezemage\Smoke\Listener;


use Freezemage\Config\ConfigInterface;
use Freezemage\Smoke\ScheduleObserver;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class ScheduleListener implements ListenerInterface {
    protected $socket;
    protected $scheduler;
    protected $config;

    public function __construct(Socket $socket, Scheduler $scheduler, ConfigInterface $config) {
        $this->socket = $socket;
        $this->scheduler = $scheduler;
        $this->config = $config;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $subscriber = new ScheduleObserver($client, $this->config);
        $this->scheduler->subscribe($subscriber);
    }
}