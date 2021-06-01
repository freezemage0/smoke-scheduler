<?php


namespace Freezemage\Smoke\Queue\Handler;


use Freezemage\Smoke\Channel\Message;
use Freezemage\Smoke\Queue\RequestHandlerInterface;


abstract class MessagingRequestHandler implements RequestHandlerInterface {
    protected function processMessage(string $message): Message {
        $message = json_decode($message, true);
        return new Message($message['request'], $message['key'], $message['parameters']);
    }
}