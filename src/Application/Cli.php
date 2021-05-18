<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Smoke\Cli\Argument\Argument;
use Freezemage\Smoke\Socket\Socket;


class Cli extends SchedulerApplication {
    /** @var Argument */
    protected $argument;
    protected $input;

    public function bootstrap(): void {
        $input = substr($this->argument->getName(), 2);
        if (!empty($this->argument->getValue())) {
            $input .= ' ' . $this->argument->getValue();
        }

        $this->input = $input;
    }

    public function setArgument(Argument $argument): void {
        $this->argument = $argument;
    }

    public function run(): void {
        $socket = Socket::create(AF_UNIX, SOCK_STREAM, 0)
            ->bind($this->config->get('connection.clientName'))
            ->connect($this->config->get('connection.serverName'));

        $input = substr($this->argument->getName(), 2);
        if (!empty($this->argument->getValue())) {
            $input .= ' ' . $this->argument->getValue();
        }

        $socket->write($input);
        $response = $socket->read($this->config->get('server.bufferSize'));

        echo $response;
        exit(0);
    }
}