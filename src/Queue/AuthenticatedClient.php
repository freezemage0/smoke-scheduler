<?php


namespace Freezemage\Smoke\Queue;


use Freezemage\Smoke\Authorization\Token;


class AuthenticatedClient implements ClientInterface {
    protected $client;
    /**
     * @var RequestHandlerInterface
     */
    protected $requestHandler;
    /**
     * @var Token
     */
    protected $token;

    public function __construct(ClientInterface $client) {
        $this->client = $client;
    }

    public function receive(): string {
        $receive = $this->client->receive();

        if (strlen($receive) > 1) {
            $receive = json_decode($receive, true, 512, JSON_UNESCAPED_UNICODE);
            if (json_last_error() != JSON_ERROR_NONE) {
                throw new \Exception('Invalid json request.');
            }
        }

        return $receive;
    }

    public function send(string $response): void {
        $this->client->send($response);
    }

    public function setRequestHandler(RequestHandlerInterface $requestHandler): void {
        $this->requestHandler = $requestHandler;
    }

    public function getRequestHandler(): RequestHandlerInterface {
        return $this->requestHandler;
    }

    public function isTokenValid(string $token): bool {
        return $token == $this->token->getValue();
    }

    public function isConnected(): bool {
        return $this->client->isConnected();
    }
}