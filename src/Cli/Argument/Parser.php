<?php


namespace Freezemage\Smoke\Cli\Argument;


use ArgumentCountError;
use InvalidArgumentException;


class Parser {
    public function getArgumentList(): ArgumentList {
        global $argv;

        array_shift($argv); // removing script name from argument list

        $arguments = new ArgumentList();

        $command = count($argv) > 0 ? array_shift($argv) : 'list';
        $arguments->add(new Argument('command', $command));

        while (!empty($argv)) {
            $argument = array_shift($argv);
            if (strpos($argument, '=') !== false) {
                list($name, $value) = explode('=', $argument);
            } else {
                $name = $argument;
                $value = null;
            }

            $arguments->add(new Argument($name, $value));
        }

        return $arguments;
    }
}