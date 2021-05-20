<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Smoke\Cli\Argument\ArgumentList;


class ResumeCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'resume';
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

        $task->activate();
        return sprintf('Task %s resumed.', $task->toString());
    }
}