<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Socket;

use SplQueue;


class Server {
    protected $listeners;

    public function __construct() {
        $this->listeners = new SplQueue();
    }

    public function addListener(ListenerInterface $listener) {
        $this->listeners->enqueue($listener);
    }

    public function accept(): void {
        $queue = new SplQueue();

        while (!$this->listeners->isEmpty()) {
            /** @var ListenerInterface $listener */
            $listener = $this->listeners->dequeue();
            $client = $listener->getSocket()->accept();

            if ($client != null) {
                $client->setBlocking(false);
                $listener->handle($client);
            }

            $queue->enqueue($listener);
        }

        $this->listeners = $queue;
    }
}