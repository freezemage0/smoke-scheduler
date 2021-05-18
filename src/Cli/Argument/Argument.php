<?php


namespace Freezemage\Smoke\Cli\Argument;


class Argument {
    /** @var string */
    protected $name;
    protected $value;

    public function __construct(string $name, $value = null) {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }
}