<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli\Command;

use Freezemage\Smoke\Scheduler;


abstract class Command implements CommandInterface {
    protected $scheduler;

    public function __construct(Scheduler $scheduler) {
        $this->scheduler = $scheduler;
    }
}