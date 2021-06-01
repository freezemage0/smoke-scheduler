<?php


namespace Freezemage\Smoke\Queue;


class Heartbeat implements ClientSubscriberInterface {
    private $interval;
    private $character;
    private $lastBeatTs;

    public function __construct(int $interval, string $character) {
        $this->interval = $interval;
        $this->character = $character;
        $this->lastBeatTs = time();
    }

    public function getInterval(): int {
        return $this->interval;
    }

    public function getCharacter(): string {
        return $this->character;
    }

    public function notify(ClientInterface $client): void {
        $now = time();
        $message = $client->receive();

        if (mb_strlen($message) > 1 || $message == $this->character) {
            $this->lastBeatTs = $now;
        }

        if (abs($now - $this->lastBeatTs) > $this->interval) {
            $client->disconnect();
        }
    }
}