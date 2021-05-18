<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Smoke\Cli\Command\CommandCollection;
use Freezemage\Smoke\Cli\Command\StartCommand;
use Freezemage\Smoke\Listener\CliListener;
use Freezemage\Smoke\Listener\ScheduleListener;
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
            $this->config
        ));

        $server->addListener(new CliListener(
            $socketFactory->createUnix($this->config->get('connection.serverName')),
            $scheduler
        ));

        $this->server = $server;
        $this->scheduler = $scheduler;
    }

    public function run(): void {
        while (true) {
            sleep(1);
            $this->scheduler->update();
            $this->server->accept();
        }
    }
}