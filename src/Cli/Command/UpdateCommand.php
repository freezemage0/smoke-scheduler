<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use DateInterval;
use DateTime;
use Freezemage\Smoke\Cli\Argument\ArgumentList;


class UpdateCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'update';
    }

    public function process(ArgumentList $argumentList): string {
        $id = $argumentList->getByName('id');
        if ($id == null) {
            return 'Task ID is not set.';
        }

        $task = $this->scheduler->getTaskById($id->getValue());
        if ($task == null) {
            return 'Task not found.';
        }

        $description = $argumentList->getByName('description');
        $expiresIn = $argumentList->getByName('expiresIn');
        $expiresAt = $argumentList->getByName('expiresAt');

        if ($description != null) {
            $task->setDescription($description->getValue());
        }

        $expires = $task->getExpiresAt();
        if ($expiresIn != null) {
            $expires->add(DateInterval::createFromDateString($expiresIn->getValue()));
        } elseif ($expiresAt != null) {
            $expires = DateTime::createFromFormat('H:i:s', $expiresAt->getValue());
        } else {
            $expires->add(DateInterval::createFromDateString('1 hour'));
        }

        $task->setExpiresAt($expires);
        return sprintf('Task %s updated.', $task->toString());
    }

    protected function getTaskKeys(): array {
        return array('description', 'expiresAt', 'expiresIn');
    }
}