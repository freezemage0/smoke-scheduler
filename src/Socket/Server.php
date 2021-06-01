<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Socket;


use Freezemage\Smoke\Queue\Client;
use Freezemage\Smoke\Queue\Handler\Authenticator;
use Freezemage\Smoke\Queue\Handler\Greeter;
use Freezemage\Smoke\Queue\HeartbeatClient;
use Freezemage\Smoke\Queue\Listener;
use Freezemage\Smoke\Queue\ListenerInterface;
use SplQueue;


class Server {
    protected $listeners;
    protected $clients;
    protected $sockets;

    public function __construct() {
        $this->listeners = new SplQueue();
        $this->clients = new SplQueue();
        $this->sockets = new SplQueue();
    }

    public function addListener(ListenerInterface $listener) {
        $this->listeners->enqueue($listener);
    }

    public function accept(): void {
        $queue = new SplQueue();

        while (!$this->listeners->isEmpty()) {
            /** @var ListenerInterface $listener */
            $listener = $this->listeners->dequeue();
            $accepted = $listener->getSocket()->accept();

            if ($accepted != null) {
                $accepted->setBlocking(false);

                $client = new Client($accepted);
                $client->addSubscription(new Greeter());

                $this->clients->enqueue($client);
            }

            $queue->enqueue($listener);
        }

        $this->listeners = $queue;
    }

    public function handle(): void {
        $queue = new SplQueue();

        while (!$this->clients->isEmpty()) {
            /** @var Client $client */
            $client = $this->clients->dequeue();

            if (!$client->isConnected()) {
                continue;
            }

            $client->update();
            $queue->enqueue($client);
        }

        $this->clients = $queue;
    }

    public function __destruct() {
        while (!$this->listeners->isEmpty()) {
            /** @var Listener $listener */
            $listener = $this->listeners->dequeue();
            $listener->getSocket()->shutdown(2);
        }
    }
}