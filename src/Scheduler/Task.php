<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Scheduler;

use DateTime;
use DateTimeInterface;


class Task {
    protected $id;
    protected $expiresAt;
    protected $description;
    protected $active;

    public function __construct(int $id, DateTimeInterface $expiresAt, string $description) {
        $this->id = $id;
        $this->expiresAt = $expiresAt;
        $this->description = $description;
        $this->active = true;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getExpiresAt(): DateTimeInterface {
        return $this->expiresAt;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function activate(): void {
        $this->active = true;
    }

    public function deactivate(): void {
        $this->active = false;
    }

    public function isActive(): bool {
        return $this->active;
    }

    public function isFinished(): bool {
        return (new DateTime())->getTimestamp() >= $this->expiresAt->getTimestamp();
    }

    public function toString(): string {
        return sprintf(
                '[id: %s, expiresAt: %s, description: %s]',
                $this->getId(),
                $this->getExpiresAt()->format('Y-m-d H:i:s'),
                $this->getDescription()
        );
    }
}