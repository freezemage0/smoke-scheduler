<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use DateTime;
use DateTimeInterface;
use Freezemage\Smoke\Cli\Argument\ArgumentList;


class StatusCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'status';
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

        $properties = array(
                'id' => $task->getId(),
                'expiresAt' => $task->getExpiresAt()->format('Y-m-d H:i:s'),
                'description' => $task->getDescription(),
                'state' => $task->isActive() ? 'active' : 'inactive',
                'timeLeft' => $this->getTimeLeft($task->getExpiresAt()),
        );

        $result = array();
        foreach ($properties as $column => $value) {
            $result[] = sprintf('%s: %s', $column, $value);
        }

        return implode(PHP_EOL, $result);
    }

    protected function getTimeLeft(DateTimeInterface $expiresAt): string {
        $now = new DateTime();
        $difference = $now->diff($expiresAt);

        return sprintf(
                '%d days, %d hours, %d minutes, %d seconds',
                $difference->d,
                $difference->h,
                $difference->i,
                $difference->s
        );
    }
}