<?php


namespace Freezemage\Smoke\Queue\Handler;


use Freezemage\Smoke\Authorization\Token;
use Freezemage\Smoke\Channel\Message;
use Freezemage\Smoke\Queue\Authentication;
use Freezemage\Smoke\Queue\ClientInterface;
use Freezemage\Smoke\Queue\ClientSubscriberInterface;


class Authenticator implements ClientSubscriberInterface {
    public function notify(ClientInterface $client): void {
        $message = Message::json($client);

        if ($message->getRequest() != 'authenticate') {
            $client->send('jopa');
            $client->disconnect();
        } else {
            $token = new Token(0, md5(time()), 0);
            $authentication = new Authentication($token);
            $client->addSubscription($authentication);
        }

        $client->removeSubscription($this);
    }
}