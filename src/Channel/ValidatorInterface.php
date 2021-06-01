<?php


namespace Freezemage\Smoke\Channel;


interface ValidatorInterface {
    public function validate(array $data): void;
}