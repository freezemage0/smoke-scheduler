<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Scheduler;


use SplObjectStorage;


class TaskLogger {
    protected $tasks;

    public function __construct() {
        $this->tasks = new SplObjectStorage();
    }

    public function store(Task $task): void {
        $this->tasks->attach($task);
    }

    /**
     * @return Task[]
     */
    public function getAll(): array {
        $tasks = array();
        foreach ($this->tasks as $task) {
            $tasks[] = $task;
        }

        return $tasks;
    }
}