<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use Freezemage\Smoke\Scheduler\Task;


interface ObserverInterface {
    public function notify(Task $task): void;

    public function hasDisconnected(): bool;
}