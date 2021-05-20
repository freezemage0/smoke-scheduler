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

        $errno = socket_last_error();

        if ($errno != 0) {
            throw new SocketException(socket_strerror($errno), $errno);
        }

        return $socket;
    }

    public function bind(string $address, int $port = 0): Socket {
        $this->checkResource();
        socket_bind($this->resource, $address, $port);
        $this->checkError();

        return $this;
    }

    public function setBlocking(bool $blocking): Socket {
        $this->checkResource();

        if ($blocking) {
            socket_set_block($this->resource);
        } else {
            socket_set_nonblock($this->resource);
        }
        $this->checkError();
        return $this;
    }

    public function connect(string $address, int $port = 0): Socket {
        $this->checkResource();
        socket_connect($this->resource, $address, $port);
        $this->checkError();

        return $this;
    }

    public function setOption(int $option, $value): Socket {
        $this->checkResource();
        socket_set_option($this->resource, SOL_SOCKET, $option, $value);
        $this->checkError();

        return $this;
    }

    public function listen(): Socket {
        $this->checkResource();
        socket_listen($this->resource);
        $this->checkError();

        return $this;
    }

    public function accept(): ?Socket {
        $this->checkResource();
        $client = socket_accept($this->resource);
        return ($client != null) ? Socket::fromResource($client) : null;
    }

    public function read(int $length): string {
        $this->checkResource();
        $result = socket_read($this->resource, $length);

        if ($result === false && socket_last_error() != SOCKET_EAGAIN) {
            $this->checkError();
        }

        return $result;
    }

    public function close(): void {
        if ($this->isClosed()) {
            return;
        }

        socket_close($this->resource);
        unset($this->resource);
    }

    public function write(string $data): void {
        $this->checkResource();
        socket_write($this->resource, $data);
        $this->checkError();
    }

    public function isClosed(): bool {
        return empty($this->resource);
    }

    protected function checkResource(): void {
        if (empty($this->resource) || get_resource_type($this->resource) != 'Socket') {
            throw new SocketException('Missing socket resource.');
        }
    }

    protected function checkError(): void {
        $errno = socket_last_error($this->resource);

        if ($errno != 0) {
            throw new SocketException(socket_strerror($errno), $errno);
        }

        socket_clear_error($this->resource);
    }

    public function shutdown(int $mode): void {
        if ($this->isClosed()) {
            return;
        }

        socket_shutdown($this->resource, $mode);
        $this->checkError();
    }

    public function __destruct() {
        $this->shutdown(2);
        $this->close();
    }
}