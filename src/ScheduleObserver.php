<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use Freezemage\Config\ConfigInterface;
use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Socket\Socket;
use Freezemage\Smoke\Socket\SocketException;


class ScheduleObserver {
    protected $socket;
    protected $notifications;

    public function __construct(Socket $socket, NotificationCollection $notifications) {
        $this->socket = $socket;
        $this->notifications = $notifications;
    }

    public function notify(Scheduler $scheduler): void {
        try {
            if ($scheduler->isRunning() && $scheduler->timeLeft() <= 0) {
                $this->socket->write($this->notifications->getRandom());
            }
        } catch (SocketException $exception) {
            echo $exception;
            $this->socket->close();
        }
    }

    public function hasDisconnected(): bool {
        return $this->socket->isClosed();
    }
}