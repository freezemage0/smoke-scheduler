<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Smoke\Cli\Argument\ArgumentList;


class UnknownCommand implements CommandInterface {
    public function canProcess(string $command): bool {
        return false;
    }

    public function process(ArgumentList $argumentList): string {
        return 'Unknown command';
    }
}