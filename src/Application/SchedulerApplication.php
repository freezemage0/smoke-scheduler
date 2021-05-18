<?php


namespace Freezemage\Smoke\Application;


use Freezemage\Config\ConfigInterface;


abstract class SchedulerApplication {
    /** @var ConfigInterface */
    protected $config;

    public function __construct(ConfigInterface $config) {
        $this->config = $config;
    }

    abstract public function bootstrap(): void;

    abstract public function run(): void;
}