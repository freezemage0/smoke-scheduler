<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Socket\Socket;
use SplObjectStorage;


final class Client implements ClientInterface {
    protected $socket;
    protected $requestHandler;
    protected $cache;
    protected $subscribers;

    public function __construct(Socket $socket) {
        $this->socket = $socket;
        $this->subscribers = new SplObjectStorage();
    }

    public function isConnected(): bool {
        return isset($this->socket) && !$this->socket->isClosed();
    }

    public function receive(): string {
        if (!isset($this->cache)) {
            $this->cache = $this->socket->read(4096);
        }

        return $this->cache;
    }

    public function update(): void {
        foreach ($this->subscribers as $subscriber) {
            if ($this->isConnected()) {
                $subscriber->notify($this);
            }
        }

        $this->clear();
    }

    public function clear(): void {
        $this->cache = null;
    }

    public function addSubscription(ClientSubscriberInterface $clientSubscriber): void {
        $this->subscribers->attach($clientSubscriber);
    }

    public function removeSubscription(ClientSubscriberInterface $clientSubscriber): void {
        $this->subscribers->detach($clientSubscriber);
    }

    public function send(string $response): void {
        $this->socket->write($response);
    }

    public function disconnect() {
        unset($this->socket);
    }
}