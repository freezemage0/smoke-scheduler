<?php


namespace Freezemage\Smoke\Queue\Routing;


use Freezemage\Smoke\Queue\Client;
use Freezemage\Smoke\Queue\ClientInterface;
use Freezemage\Smoke\Queue\RequestHandlerInterface;


class Router {
    /** @var RequestHandlerInterface[] */
    protected $requestHandlers;

    public function __construct(array $requestHandlers = array()) {
        foreach ($requestHandlers as $handler) {
            $this->addRequestHandler($handler);
        }
    }

    public function addRequestHandler(RequestHandlerInterface $requestHandler) {
        $this->requestHandlers[] = $requestHandler;
    }

    public function resolve(ClientInterface $client): ClientInterface {
        foreach ($this->requestHandlers as $handler) {
            if ($handler->canHandle($client)) {
                return $handler->handle($client);
            }
        }

        return $client;
    }
}