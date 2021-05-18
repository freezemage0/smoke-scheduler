<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Smoke\Cli\Argument\Argument;
use Freezemage\Smoke\Socket\Socket;


class Cli extends SchedulerApplication {
    /** @var Argument */
    protected $argument;
    protected $input;

    public function bootstrap(): void {
        $input = array(
                'command' => $this->argument->getName(),
                'argument' => $this->argument->getValue()
        );
        $this->input = json_encode($input);
    }

    public function setArgument(Argument $argument): void {
        $this->argument = $argument;
    }

    public function run(): void {
        $socket = Socket::create(AF_UNIX, SOCK_STREAM, 0)
            ->bind($this->config->get('connection.clientName'))
            ->connect($this->config->get('connection.serverName'));
        
        $socket->write($this->input);
        $response = $socket->read($this->config->get('server.bufferSize'));

        echo $response;
        $socket->close();
        unlink($this->config->get('connection.clientName'));
        
        exit(0);
    }
}