<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\AutoAssignment;


use DateInterval;
use DateTime;
use Freezemage\Smoke\ObserverInterface;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Scheduler\Task;


class Observer implements ObserverInterface {
    private $config;
    private $scheduler;

    /**
     * This is wrong. We cannot grant Observer access to Scheduler.
     * TODO: remove circular dependency.
     */
    public function __construct(Config $config, Scheduler $scheduler) {
        $this->config = $config;
        $this->scheduler = $scheduler;
    }

    public function notify(Task $task): void {
        if (!$this->config->isEnabled()) {
            return;
        }

        $expiresAt = $task->getExpiresAt();
        $expires = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                $expiresAt->format('Y-m-d H:i:s')
        );
        $expires->add(DateInterval::createFromDateString($this->config->getExpiresIn()));
        $task = $this->scheduler->createTask($expires);
        $this->scheduler->start($task);
    }

    public function hasDisconnected(): bool {
        return false;
    }
}