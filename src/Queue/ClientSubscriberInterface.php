<?php


namespace Freezemage\Smoke\Queue;


interface ClientSubscriberInterface {
    public function notify(ClientInterface $client): void;
}