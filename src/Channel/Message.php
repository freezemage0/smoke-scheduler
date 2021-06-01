<?php


namespace Freezemage\Smoke\Channel;


class Message {
    protected $request;
    protected $token;
    protected $parameters;
    protected $raw;

    public static function json(string $json): Message {
        $message = new Message();
        $message->raw = $json;

        $json = json_decode($json, true);
        $message->request = $json['request'];
        $message->token = $json['token'] ?? null;
        $message->parameters = $json['parameters'] ?? array();

        return $message;
    }

    public static function raw(string $raw): Message {
        $message = new Message();
        $message->raw = $raw;

        return $message;
    }


    public function getRequest(): string {
        return $this->request;
    }

    public function getToken(): ?string {
        return $this->token;
    }

    public function getParameters(): array {
        return $this->parameters;
    }

    public function getRaw(): string {
        return $this->raw;
    }

    public function getParameter(string $name) {
        return $this->parameters[$name] ?? null;
    }

    public function hasParameter(string $name): bool {
        return array_key_exists($name, $this->parameters);
    }
}