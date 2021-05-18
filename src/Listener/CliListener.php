<?php


namespace Freezemage\Smoke\Listener;


use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class CliListener implements ListenerInterface {
    protected $socket;
    protected $scheduler;

    public function __construct(Socket $socket, Scheduler $scheduler) {
        $this->socket = $socket;
        $this->scheduler = $scheduler;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $data = $client->read(1024);
        if ($data == null) {
            return;
        }

        $arguments = explode(' ', $data);
        $command = array_shift($arguments);
        $response = $this->processCommand($command, $arguments);

        $client->write($response);
    }

    protected function processCommand(string $command, array $arguments): string {
        switch ($command) {
            case 'start':
                $timer = array_shift($arguments);
                if (!is_numeric($timer) || $timer < 1) {
                    $timer = 3600;
                }
                $this->scheduler->start($timer);
                return sprintf('Scheduled waiting for %s seconds.', $timer);
            case 'stop':
                $this->scheduler->stop();
                return 'Timer stopped';
            case 'pause':
                $this->scheduler->pause();
                return 'Timer paused';
            case 'resume':
                $this->scheduler->resume();
                return sprintf('Timer resumed. Time left: %s', $this->scheduler->timeLeft());
            default:
                return 'Unknown command';
        }
    }
}