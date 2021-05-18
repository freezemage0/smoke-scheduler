<?php


namespace Freezemage\Smoke\Listener;


use Freezemage\Config\ConfigInterface;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class CliListener implements ListenerInterface {
    protected $socket;
    protected $scheduler;
    protected $config;

    public function __construct(Socket $socket, Scheduler $scheduler, ConfigInterface $config) {
        $this->socket = $socket;
        $this->scheduler = $scheduler;
        $this->config = $config;
    }

    public function getSocket(): Socket {
        return $this->socket;
    }

    public function handle(Socket $client): void {
        $data = $client->read($this->config->get('server.bufferSize'));
        if ($data == null) {
            return;
        }

        $input = json_decode($data, true);
        $response = $this->processCommand($input);
        $client->write($response . PHP_EOL);
    }

    protected function processCommand(array $input): string {
        $command = $input['command'] ?? null;
        $argument = $input['argument'] ?? null;
        
        switch ($command) {
            case '--start':
                if (!is_numeric($argument) || $argument < 1) {
                    $argument = 3600;
                }
                $this->scheduler->start($argument);
                return sprintf('Scheduled waiting for %s seconds.', $argument);
            case '--stop':
                $this->scheduler->stop();
                return 'Timer stopped';
            case '--pause':
                $this->scheduler->pause();
                return 'Timer paused';
            case '--resume':
                $this->scheduler->resume();
                return sprintf('Timer resumed. Time left: %s', $this->scheduler->timeLeft());
            default:
                return 'Unknown command';
        }
    }
}