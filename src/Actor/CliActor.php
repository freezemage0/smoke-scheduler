<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Actor;

use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\Socket;


class CliActor implements ActorInterface {
    /** @var Scheduler $scheduler */
    protected $scheduler;

    public function __construct(Scheduler $scheduler) {
        $this->scheduler = $scheduler;
    }

    public function process(Socket $socket): ?string {
        $data = '';
        do {
            $part = $socket->read(1024);
            $data .= $part;
        } while (empty($data) && !empty($part));

        $arguments = explode(' ', $data);
        $command = array_shift($arguments);

        if ($command == 'start') {
            $until = array_shift($arguments);
            $this->scheduler->start($until);
            return "Scheduled for $until seconds.";
        }

        return 'Unknown command.';
    }
}