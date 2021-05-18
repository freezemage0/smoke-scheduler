<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Socket;


use Exception;


class Socket {
    protected $resource;

    public static function fromResource($resource): Socket {
        $socket = new Socket();
        $socket->resource = $resource;

        return $socket;
    }

    public static function create(int $protocol, int $type, int $domain): Socket {
        $socket = new Socket();
        $socket->resource = socket_create($protocol, $type, $domain);

        return $socket;
    }

    public function bind(string $address, int $port = 0): Socket {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        socket_bind($this->resource, $address, $port);
        return $this;
    }

    public function setBlocking(bool $blocking): Socket {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        if ($blocking) {
            socket_set_block($this->resource);
        } else {
            socket_set_nonblock($this->resource);
        }

        return $this;
    }

    public function connect(string $address, int $port = 0): Socket {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        socket_connect($this->resource, $address, $port);
        return $this;
    }

    public function setOption(int $option, $value): Socket {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        socket_set_option($this->resource, SOL_SOCKET, $option, $value);
        return $this;
    }

    public function listen(): Socket {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        socket_listen($this->resource);
        return $this;
    }

    public function accept(): ?Socket {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        $client = socket_accept($this->resource);
        return ($client != null) ? Socket::fromResource($client) : null;
    }

    public function read(int $length): string {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        return socket_read($this->resource, $length);
    }

    public function close(): void {
        if (empty($this->resource)) {
            return;
        }

        socket_close($this->resource);
        unset($this->resource);
    }

    public function write(string $data): void {
        if (empty($this->resource)) {
            throw new Exception('Missing resource.');
        }

        socket_write($this->resource, $data);
    }

    public function isClosed(): bool {
        return empty($this->resource);
    }

    public function __destruct() {
        $this->close();
    }
}