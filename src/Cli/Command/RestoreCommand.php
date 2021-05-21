<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use DateTime;
use Freezemage\Config\ConfigFactory;
use Freezemage\Smoke\Cli\Argument\ArgumentList;


class RestoreCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'import';
    }

    public function process(ArgumentList $argumentList): string {
        $import = $argumentList->getByName('import');
        if ($import == null) {
            return 'Import filename is not set.';
        }

        if (!is_file($import->getValue())) {
            return 'Import file does not exist.';
        }

        $factory = new ConfigFactory();
        $config = $factory->create($import->getValue());

        $tasks = $config->get('tasks');
        foreach ($tasks as $task) {
            $expiresAt = DateTime::createFromFormat(DATE_ATOM, $task['expiresAt']);
            $entry = $this->scheduler->createTask($expiresAt, $task['description']);
            $this->scheduler->start($entry);
        }

        return sprintf('%s tasks imported. Run "list" command for more info.', count($tasks));
    }
}