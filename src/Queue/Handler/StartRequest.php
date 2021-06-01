<?php


namespace Freezemage\Smoke\Queue\Handler;


use Freezemage\Smoke\Queue\AuthenticatedClient;
use Freezemage\Smoke\Queue\ClientInterface;
use Freezemage\Smoke\Queue\RequestHandlerInterface;


class StartRequest implements RequestHandlerInterface {
    public function handle(ClientInterface $client): ClientInterface {
        $data = $client->receive();
        $json = json_decode($data, true);

        if (!($client instanceof AuthenticatedClient)) {
            throw new \Exception('Pidor... Ne nado vzlamivat....');
        }

        if (!array_key_exists('key', $json) || !$client->isTokenValid($json['key'])) {
            throw new \Exception('Pidor... Ne nado vzlamivat....');
        }

        $client->send('Task enqueued.');
        return $client;
    }
}