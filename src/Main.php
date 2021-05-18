<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use Freezemage\Config\ConfigFactory;
use Freezemage\Smoke\Application\Cli;
use Freezemage\Smoke\Application\Daemon;
use Freezemage\Smoke\Cli\Argument\Parser;
use RuntimeException;


class Main {
    public function __construct() {
        if (strtolower(PHP_OS_FAMILY) != 'linux') {
            throw new RuntimeException('Unsupported OS.');
        }
    }

    public function run(): void {
        $parser = new Parser();
        $argument = $parser->getArgument();

        $factory = new ConfigFactory();
        $directory = dirname(__DIR__);
        $config = $factory->create($directory . '/config.json');

        if ($argument->getName() == '--daemonize') {
            $application = new Daemon($config);
        } else {
            $application = new Cli($config);
            $application->setArgument($argument);
        }

        $application->bootstrap();
        $application->run();
    }
}