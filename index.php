<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */

require_once __DIR__ . '/vendor/autoload.php';


$s = new \Freezemage\Smoke\Socket\Server();
$scheduler = new \Freezemage\Smoke\Scheduler();

$schedulerListener = new \Freezemage\Smoke\Socket\Listener(
        \Freezemage\Smoke\Socket\Socket::create(AF_INET, SOCK_STREAM, SOL_TCP)
            ->bind('192.168.1.105', '3411')
            ->setOption(SO_REUSEADDR, true)
            ->setBlocking(false)
            ->listen(),
        new \Freezemage\Smoke\Actor\ScheduleActor($scheduler)
);

$cliListener = new \Freezemage\Smoke\Socket\Listener(
        \Freezemage\Smoke\Socket\Socket::create(AF_UNIX, SOCK_STREAM, 0)
            ->bind('smoke-scheduler-server')
            ->setOption(SO_REUSEADDR, true)
            ->setBlocking(false)
            ->listen(),
        new \Freezemage\Smoke\Actor\CliActor($scheduler)
);

$s->addListener($schedulerListener);
$s->addListener($cliListener);

while (true) {
    sleep(1);
    if ($scheduler->isRunning()) {
        var_dump('Iterating...');
        $scheduler->iterate();
        var_dump($scheduler->timeLeft());
    }

    $s->accept();

    if ($scheduler->timeLeft() <= 0) {
        $scheduler->update();
        $scheduler->stop();
    }
}