<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\AutoAssignment;


class Config {
    protected $enabled;
    protected $expiresIn;

    public function __construct(bool $enabled, string $expiresIn) {
        $this->enabled = $enabled;
        $this->expiresIn = $expiresIn;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function disable(): void {
        $this->enabled = false;
    }

    public function enable(): void {
        $this->enabled = true;
    }

    public function getExpiresIn(): string {
        return $this->expiresIn;
    }
}