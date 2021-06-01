<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Socket\Socket;


interface ClientInterface {
    public function isConnected(): bool;

    public function receive(): string;

    public function send(string $response): void;

    public function addSubscription(ClientSubscriberInterface $clientSubscriber): void;

    public function removeSubscription(ClientSubscriberInterface $clientSubscriber): void;

    public function disconnect();
}