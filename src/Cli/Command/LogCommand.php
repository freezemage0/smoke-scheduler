<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;


use Freezemage\Smoke\Cli\Argument\ArgumentList;
use Freezemage\Smoke\Scheduler\Task;


class LogCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'log';
    }

    public function process(ArgumentList $argumentList): string {
        $logger = $this->scheduler->getLogger();
        $taskList = array_map(
                function (Task $task): array {
                    return array(
                            'id' => $task->getId(),
                            'since' => $task->getSince()->format('Y-m-d H:i:s'),
                            'description' => $task->getDescription(),
                            'expiresAt' => $task->getExpiresAt()->format('Y-m-d H:i:s'),
                            'state' => $task->isActive() ? 'Active' : 'Inactive',
                            'finished' => $task->isFinished() ? 'Yes' : 'No'
                    );
                },
                $logger->getAll()
        );

        return json_encode($taskList, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}