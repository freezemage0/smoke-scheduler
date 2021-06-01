<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Authorization\Token;
use Freezemage\Smoke\Channel\Message;


class Authentication implements ClientSubscriberInterface {
    private $token;

    public function __construct(Token $token) {
        $this->token = $token;
    }

    public function notify(ClientInterface $client): void {
        $message = Message::json($client);

        if (!$message->getToken() != $this->token->getValue()) {
            $client->disconnect();
        }
    }
}