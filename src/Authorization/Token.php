<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Authorization;


class Token {
    protected $value;
    protected $uid;
    protected $since;

    public function __construct(int $uid, string $value, int $since) {
        $this->uid = $uid;
        $this->value = $value;
        $this->since = $since;
    }

    public function getUid(): int {
        return $this->uid;
    }

    public function getSince(): int {
        return $this->since;
    }

    public function getValue(): string {
        return $this->value;
    }
}