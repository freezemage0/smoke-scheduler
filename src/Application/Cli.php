<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Smoke\Cli\Argument\Argument;
use Freezemage\Smoke\Cli\Argument\ArgumentList;
use Freezemage\Smoke\Socket\Socket;


class Cli extends SchedulerApplication {
    /** @var ArgumentList */
    protected $argumentList;
    protected $input;

    public function bootstrap(): void {
        $arguments = $this->argumentList->getAll();
        $input = array();
        foreach ($arguments as $argument) {
            if ($argument->getName() == '--command') {
                $input['command'] = $argument->getValue();
            }
            $input['arguments'][$argument->getName()] = $argument->getValue();
        }
        if (empty($input['command'])) {
            $input['command'] = 'list'; // todo: help command
        }
        $this->input = json_encode($input);
    }

    public function setArgumentList(ArgumentList $argumentList): void {
        $this->argumentList = $argumentList;
    }

    public function run(): void {
        $socket = Socket::create(AF_UNIX, SOCK_STREAM, 0)
            ->connect(sys_get_temp_dir() . '/' . $this->config->get('connection.serverName'));
        
        $socket->write($this->input);
        $response = $socket->read($this->config->get('server.bufferSize'));

        echo $response;
        $socket->close();

        exit(0);
    }
}