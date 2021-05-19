<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Smoke\Listener\CliListener;
use Freezemage\Smoke\Listener\ScheduleListener;
use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Scheduler;
use Freezemage\Smoke\Socket\Server;
use Freezemage\Smoke\Socket\ServerSocketFactory;


class Daemon extends SchedulerApplication {
    /**
     * @var Server
     */
    protected $server;
    /**
     * @var Scheduler
     */
    protected $scheduler;
    
    protected $isRunning;

    public function bootstrap(): void {
        $server = new Server();
        $scheduler = new Scheduler();
        $socketFactory = new ServerSocketFactory();

        $server->addListener(new ScheduleListener(
            $socketFactory->createTcp(
                $this->config->get('server.address'),
                $this->config->get('server.port')
            ),
            $scheduler,
            NotificationCollection::fromConfig($this->config)
        ));

        $server->addListener(new CliListener(
            $socketFactory->createUnix($this->config->get('connection.serverName')),
            $scheduler,
            $this->config
        ));

        $this->server = $server;
        $this->scheduler = $scheduler;
        $this->isRunning = true;
    }

    public function run(): void {
        pcntl_async_signals(true);
        $callback = function () {
            echo 'Shutting down gracefully...' . PHP_EOL;
            $this->isRunning = false;
        };
        
        pcntl_signal(SIGINT, $callback);
        pcntl_signal(SIGTERM, $callback);
        
        while ($this->isRunning) {
            sleep(1);
            pcntl_signal_dispatch();
            $this->scheduler->update();
            $this->server->accept();
        }
    }
}