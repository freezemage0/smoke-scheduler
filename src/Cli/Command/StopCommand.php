<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Smoke\Cli\Argument\ArgumentList;


class StopCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'stop';
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

        $this->scheduler->stop($task);
        return sprintf('Task %s stopped.', $task->toString());
    }
}