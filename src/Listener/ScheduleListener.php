<?php


namespace Freezemage\Smoke\Listener;


use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\ScheduleObserver;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class ScheduleListener implements ListenerInterface {
    protected $socket;
    protected $scheduler;
    protected $notifications;

    public function __construct(Socket $socket, Scheduler $scheduler, NotificationCollection $notifications) {
        $this->socket = $socket;
        $this->scheduler = $scheduler;
        $this->notifications = $notifications;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $subscriber = new ScheduleObserver($client, $this->notifications);
        $this->scheduler->subscribe($subscriber);
    }
}