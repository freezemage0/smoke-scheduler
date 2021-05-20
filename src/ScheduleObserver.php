<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Scheduler\Task;
use Freezemage\Smoke\Socket\Socket;
use Freezemage\Smoke\Socket\SocketException;


class ScheduleObserver {
    protected $socket;
    protected $notifications;

    public function __construct(Socket $socket, NotificationCollection $notifications) {
        $this->socket = $socket;
        $this->notifications = $notifications;
    }

    public function notify(Task $task): void {
        try {
            $this->socket->write($task->getDescription());
        } catch (SocketException $exception) {
            echo $exception;
            $this->socket->close();
        }
    }

    public function hasDisconnected(): bool {
        return $this->socket->isClosed();
    }
}