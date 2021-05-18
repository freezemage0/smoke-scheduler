<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;

use Freezemage\Config\ConfigInterface;
use Freezemage\Smoke\Socket\Socket;


class ScheduleObserver {
    protected $socket;
    protected $config;

    public function __construct(Socket $socket, ConfigInterface $config) {
        $this->socket = $socket;
        $this->config = $config;
    }

    public function notify(Scheduler $scheduler): void {
        if ($scheduler->isRunning() && $scheduler->timeLeft() <= 0) {
            $phrases = $this->config->get('notification.phrases');
            $phraseIndex = rand(0, count($phrases) - 1);
            
            $this->socket->write($phrases[$phraseIndex]);
        }
    }
}