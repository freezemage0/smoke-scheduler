<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;


use Freezemage\Smoke\Cli\Argument\ArgumentList;


interface CommandInterface {
    public function canProcess(string $command): bool;

    public function process(ArgumentList $argumentList): string;
}