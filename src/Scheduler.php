<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use DateTimeInterface;
use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Scheduler\Task;
use SplObjectStorage;
use SplQueue;


class Scheduler {
    protected $id;
    protected $tasks;
    protected $subscribers;
    protected $notifications;

    public function __construct(NotificationCollection $notificationCollection) {
        $this->id = 0;
        $this->subscribers = new SplQueue();
        $this->tasks = new SplObjectStorage();
        $this->notifications = $notificationCollection;
    }

    public function createTask(DateTimeInterface $expiresAt, string $description = null): Task {
        return new Task(
                ++$this->id,
                $expiresAt,
                $description ?? $this->notifications->getRandom()
        );
    }

    public function start(Task $task): void {
        $this->tasks->attach($task);
    }

    public function stop(Task $task): void {
        $this->tasks->detach($task);
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array {
        $result = array();

        foreach ($this->tasks as $task) {
            $result[] = $task;
        }

        return $result;
    }

    public function getTaskById(int $id): ?Task {
        foreach ($this->tasks as $task) {
            /** @var Task $task */
            if ($task->getId() == $id) {
                return $task;
            }
        }

        return null;
    }

    public function update(): void {
        foreach ($this->tasks as $task) {
            /** @var Task $task */
            if (!$task->isActive()) {
                continue;
            }

            if ($task->isFinished()) {
                $this->notifySubscribers($task);
                $this->tasks->detach($task);
            }
        }
    }

    protected function notifySubscribers(Task $task): void {
        $queue = new SplQueue();

        while (!$this->subscribers->isEmpty()) {
            /** @var ScheduleObserver $subscriber */
            $subscriber = $this->subscribers->dequeue();
            if (!$subscriber->hasDisconnected()) {
                $subscriber->notify($task);
                $queue->enqueue($subscriber);
            }
        }

        $this->subscribers = $queue;
    }

    public function subscribe(ScheduleObserver $observer): void {
        $this->subscribers->enqueue($observer);
    }

    public function getSubscribers(): SplQueue {
        return clone $this->subscribers;
    }
}