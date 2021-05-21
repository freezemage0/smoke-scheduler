<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Config\ConfigFactory;
use Freezemage\Smoke\Cli\Argument\ArgumentList;


class PersistCommand extends Command {
    public function canProcess(string $command): bool {
        return $command == 'persist';
    }

    public function process(ArgumentList $argumentList): string {
        $factory = new ConfigFactory();
        $filename = $argumentList->getByName('export');
        if ($filename == null) {
            return 'Export filename is not set.';
        }

        $config = $factory->create($filename->getValue());
        $config->getImporter()->setFilename(''); // Bug in freezemage0/config. Remove after fix.

        $tasks = $this->scheduler->getTasks();

        $exportList = array();
        foreach ($tasks as $task) {
            $exportList[] = array(
                    'expiresAt' => $task->getExpiresAt()->format(DATE_ATOM),
                    'description' => $task->getDescription()
            );
        }

        $config->set('tasks', $exportList)->save();
        return sprintf('Tasks have been exported to %s.', $config->getExporter()->getFilename());
    }
}