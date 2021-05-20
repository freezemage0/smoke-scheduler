<?php


namespace Freezemage\Smoke\Listener;


use Freezemage\Config\ConfigInterface;
use Freezemage\Smoke\Cli\Argument\Argument;
use Freezemage\Smoke\Cli\Argument\ArgumentList;
use Freezemage\Smoke\Cli\Command\Command;
use Freezemage\Smoke\Cli\CommandFactory;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\ListenerInterface;
use Freezemage\Smoke\Socket\Socket;


class CliListener implements ListenerInterface {
    protected $socket;
    protected $scheduler;
    protected $config;
    protected $commandFactory;

    public function __construct(Socket $socket, Scheduler $scheduler, ConfigInterface $config, CommandFactory $commandFactory) {
        $this->socket = $socket;
        $this->scheduler = $scheduler;
        $this->config = $config;
        $this->commandFactory = $commandFactory;
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
        var_dump($input);
        $response = $this->processCommand($input);
        $client->write($response . PHP_EOL);
    }

    protected function processCommand(array $input): string {
        $command = $input['command'] ?? null;
        $argumentList = new ArgumentList();
        foreach ($input['arguments'] as $name => $value) {
            $argumentList->add(new Argument($name, $value));
        }

        return $this->commandFactory->getCommand($command)->process($argumentList);
    }

    public function __destruct() {
        unlink(sys_get_temp_dir() . '/' . $this->config->get('connection.serverName'));
    }
}