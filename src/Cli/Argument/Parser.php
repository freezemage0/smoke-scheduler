<?php


namespace Freezemage\Smoke\Cli\Argument;


use ArgumentCountError;
use InvalidArgumentException;


class Parser {
    public const COMMANDS = array('--daemonize', '--start', '--stop', '--pause', '--resume');

    public function getArgument(): Argument {
        global $argv;

        array_shift($argv); // removing script name from argument list

        if (count($argv) > 2) {
            throw new ArgumentCountError('Blin ppc...');
        }

        $name = array_shift($argv);
        if (!in_array($name, Parser::COMMANDS)) {
            throw new InvalidArgumentException('Unknown command.');
        }

        $value = array_shift($argv);

        return new Argument($name, $value);
    }
}