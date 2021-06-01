<?php


namespace Freezemage\Smoke\Queue\Handler;


use Freezemage\Smoke\Queue\ClientInterface;
use Freezemage\Smoke\Queue\ClientSubscriberInterface;


class Greeter implements ClientSubscriberInterface {
    public function notify(ClientInterface $client): void {
        if ($client->receive() == 'smolim?') {
            $client->send('smolim!');
            $client->addSubscription(new Authenticator());
        } else {
            $client->disconnect();
        }

        $client->removeSubscription($this);
    }
}