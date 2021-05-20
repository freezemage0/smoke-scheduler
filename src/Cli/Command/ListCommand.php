<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Smoke\Cli\Argument\ArgumentList;


class ListCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'list';
    }

    public function process(ArgumentList $argumentList): string {
        $list = $this->scheduler->getTasks();
        $description = array();

        foreach ($list as $task) {
            $description[] = $task->toString();
        }

        return implode(PHP_EOL, $description);
    }
}