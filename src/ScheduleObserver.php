<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;

use Freezemage\Config\ConfigInterface;
use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Socket\Socket;


class ScheduleObserver {
    protected $socket;
    protected $notifications;

    public function __construct(Socket $socket, NotificationCollection $notifications) {
        $this->socket = $socket;
        $this->notifications = $notifications;
    }

    public function notify(Scheduler $scheduler): void {
        if ($scheduler->isRunning() && $scheduler->timeLeft() <= 0) {
            $this->socket->write($this->notifications->getRandom());
        }
    }
}