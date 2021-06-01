<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Queue\Routing\Router;


class DeferringRequestHandler implements RequestHandlerInterface {
    protected $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    public function handle(ClientInterface $client): ClientInterface {

        return $this->router->resolve($client);
    }

    public function canHandle(ClientInterface $client): bool {
        return $client instanceof AuthenticatedClient;
    }
}