<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use SplQueue;


class Scheduler {
    protected $timeUntil;
    protected $isRunning;
    protected $subscribers;

    public function __construct() {
        $this->stop();
        $this->subscribers = new SplQueue();
    }

    public function start(int $until): void {
        $this->timeUntil = $until;
        $this->isRunning = true;
    }

    public function stop(): void {
        $this->timeUntil = 0;
        $this->isRunning = false;
    }

    public function pause(): void {
        $this->isRunning = false;
    }

    public function resume(): void {
        $this->isRunning = true;
    }

    public function isRunning(): bool {
        return $this->isRunning;
    }

    public function timeLeft(): int {
        return $this->timeUntil;
    }

    public function update(): void {
        if ($this->timeLeft() <= 0) {
            $this->stop();
        }
        
        if ($this->isRunning()) {
            $this->timeUntil -= 1;
        }
    
        $queue = new SplQueue();
    
        while (!$this->subscribers->isEmpty()) {
            /** @var ScheduleObserver $subscriber */
            $subscriber = $this->subscribers->dequeue();
            if (!$subscriber->hasDisconnected()) {
                $subscriber->notify($this);
                $queue->enqueue($subscriber);
            }
        }
    
        $this->subscribers = $queue;
    }

    public function subscribe(ScheduleObserver $observer): void {
        $this->subscribers->enqueue($observer);
    }
}