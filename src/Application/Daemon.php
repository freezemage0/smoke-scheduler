<?php


namespace Freezemage\Smoke\Application;


use DateInterval;
use DateTime;
use Freezemage\Smoke\AutoAssignment\Config;
use Freezemage\Smoke\AutoAssignment\Observer;
use Freezemage\Smoke\Cli\CommandFactory;
use Freezemage\Smoke\Listener\CliListener;
use Freezemage\Smoke\Listener\ScheduleListener;
use Freezemage\Smoke\Listener\WelcomeListener;
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
        $notificationCollection = NotificationCollection::fromConfig($this->config);
        $scheduler = new Scheduler($notificationCollection);
        $socketFactory = new ServerSocketFactory();

        $serverSocket = $socketFactory->createTcp(
                $this->config->get('server.address'),
                $this->config->get('server.port')
        );

        $server->addListener(new ScheduleListener(
                $serverSocket,
                $scheduler,
                $notificationCollection
        ));
        $server->addListener(new CliListener(
                $socketFactory->createUnix(sys_get_temp_dir() . '/' . $this->config->get('server.name')),
                $scheduler,
                $this->config,
                new CommandFactory($scheduler)
        ));

        $config = new Config(
            $this->config->get('autoAssignment.enabled'),
            $this->config->get('autoAssignment.expiresIn')
        );
        $scheduler->subscribe(new Observer($config, $scheduler));

        $presets = $this->config->get('presets', array());
        $currentTime = new DateTime();

        foreach ($presets as $preset) {
            $expires = DateTime::createFromFormat('H:i:s', $preset['expiresAt']);
            if ($currentTime->getTimestamp() >= $expires->getTimestamp()) {
                continue;
            }

            $scheduler->createTask(
                    $expires,
                    $preset['description'] ?? null
            );
        }

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
            pcntl_signal_dispatch();
        }

        exit(0);
    }
}