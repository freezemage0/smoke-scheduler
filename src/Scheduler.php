<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use DateTimeInterface;
use Freezemage\Config\ConfigFactory;
use Freezemage\Environment\Environment;
use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Scheduler\Task;
use Freezemage\Smoke\Scheduler\TaskLogger;
use SplObjectStorage;
use SplQueue;


class Scheduler {
    protected $id;
    protected $tasks;
    protected $subscribers;
    protected $notifications;
    protected $logger;

    public function __construct(NotificationCollection $notificationCollection) {
        $this->id = 0;
        $this->subscribers = new SplQueue();
        $this->tasks = new SplObjectStorage();
        $this->notifications = $notificationCollection;
        $this->logger = new TaskLogger();
    }

    public function createTask(DateTimeInterface $expiresAt, string $description = null): Task {
        $task = new Task(
                ++$this->id,
                $expiresAt,
                $description ?? $this->notifications->getRandom()
        );

        $this->logger->store($task);

        return $task;
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

    public function getLogger(): TaskLogger {
        return $this->logger;
    }

    public function update(): void {
        foreach ($this->tasks as $task) {
            /** @var Task $task */
            if (!$task->isActive()) {
                continue;
            }

            if ($task->isFinished()) {
                $this->notifySubscribers($task);
                $task->deactivate();
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

    public function subscribe(ObserverInterface $observer): void {
        $this->subscribers->enqueue($observer);
    }

    public function getSubscribers(): SplQueue {
        return clone $this->subscribers;
    }
}