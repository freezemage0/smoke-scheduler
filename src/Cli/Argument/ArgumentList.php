<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Argument;


class ArgumentList {
    protected $arguments;

    public function __construct() {
        $this->arguments = array();
    }

    public function add(Argument $argument): void {
        $this->arguments[] = $argument;
    }

    public function getAll(): array {
        return $this->arguments;
    }

    public function getByName(string $name): ?Argument {
        foreach ($this->arguments as $argument) {
            if ($argument->getName() == $name || $argument->getName() == '--' . $name) {
                return $argument;
            }
        }

        return null;
    }
}