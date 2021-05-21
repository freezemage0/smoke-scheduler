<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;


use DateInterval;
use DateTime;
use Freezemage\Smoke\Cli\Argument\ArgumentList;


class StartCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'start';
    }

    public function process(ArgumentList $argumentList): string {
        $expiresAt = $argumentList->getByName('expiresAt');
        $expiresIn = $argumentList->getByName('expiresIn');
        $description = $argumentList->getByName('description');

        $expires = new DateTime();
        if ($expiresIn != null) {
            $expires->add(DateInterval::createFromDateString($expiresIn->getValue()));
        } elseif ($expiresAt != null) {
            $expires = DateTime::createFromFormat('H:i:s', $expiresAt->getValue());
        } else {
            $expires->add(DateInterval::createFromDateString('1 hour'));
        }

        if ($description != null) {
            $description = $description->getValue();
        }

        $task = $this->scheduler->createTask($expires, $description);
        $this->scheduler->start($task);

        return sprintf('Task %s started.', $task->toString());
    }
}