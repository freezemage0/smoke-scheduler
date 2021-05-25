<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Smoke\Cli\Argument\ArgumentList;
use Freezemage\Smoke\ScheduleObserver;


class ConnectionsCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'connections';
    }

    public function process(ArgumentList $argumentList): string {
        $subscribers = $this->scheduler->getSubscribers();

        $clients = array();
        foreach ($subscribers as $subscriber) {
            /** @var ScheduleObserver $subscriber */
            if (!$subscriber->hasDisconnected()) {
                $info = $subscriber->getClientInfo();
                $clients[] = sprintf('[address: %s, port: %s]', $info->getAddress(), $info->getPort());
            }
        }

        return implode(PHP_EOL, $clients);
    }
}