<?php


namespace Freezemage\Smoke\Queue\Handler;


use Freezemage\Smoke\Channel\Message;
use Freezemage\Smoke\Queue\ClientInterface;
use Freezemage\Smoke\Queue\ClientSubscriberInterface;
use Freezemage\Smoke\Queue\Heartbeat;


class HeartbeatTracer implements ClientSubscriberInterface {
    public function notify(ClientInterface $client): void {
        $message = Message::json($client->receive());

        if ($message->getRequest() != 'heartbeat') {
            $client->send('jopa');
        }

        $interval = $message->hasParameter('interval') ? $message->getParameter('interval') : 10;
        $character = $message->hasParameter('character') ? $message->getParameter('character') : PHP_EOL;

        $heartbeat = new Heartbeat($interval, $character);
        $client->addSubscription($heartbeat);
        $client->removeSubscription($this);
    }
}