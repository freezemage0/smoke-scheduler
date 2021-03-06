<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Smoke\Cli\CommandFactory;
use Freezemage\Smoke\Listener\CliListener;
use Freezemage\Smoke\Listener\ScheduleListener;
use Freezemage\Smoke\Notification\NotificationCollection;
use Freezemage\Smoke\Queue\Listener;
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
        $notificationCollection = NotificationCollection::fromConfig($this->config);
        $scheduler = new Scheduler($notificationCollection);
        $socketFactory = new ServerSocketFactory();

        $server->addListener(new Listener(
            $socketFactory->createTcp(
                $this->config->get('server.address'),
                $this->config->get('server.port')
            )
        ));

        $server->addListener(new Listener(
            $socketFactory->createUnix(sys_get_temp_dir() . '/' . $this->config->get('server.name')),
        ));

        $this->server = $server;
        $this->scheduler = $scheduler;
        $this->isRunning = true;
    }

    public function run(): void {
        pcntl_async_signals(true);
        $callback = function () {
            echo 'Shutting down gracefully...' . PHP_EOL . PHP_EOL;
            $this->isRunning = false;
        };
        
        pcntl_signal(SIGINT, $callback);
        pcntl_signal(SIGTERM, $callback);
        
        while ($this->isRunning) {
            usleep(10000);
            $this->scheduler->update();
            $this->server->accept();
            $this->server->handle();
            pcntl_signal_dispatch();
        }

        exit(0);
    }
}