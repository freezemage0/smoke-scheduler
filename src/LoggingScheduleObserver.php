<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;

use Freezemage\Smoke\Scheduler\Task;
use SplObjectStorage;


class LoggingScheduleObserver implements ObserverInterface {
    /** @var SplObjectStorage $taskStorage */
    protected $taskStorage;

    public function notify(Task $task): void {
        $this->taskStorage->attach($task);
    }

    public function getTasks(): array {
        $tasks = array();
        foreach ($this->taskStorage as $task) {
            $tasks[] = $task;
        }

        return $tasks;
    }

    public function hasDisconnected(): bool {
        return false;
    }
}