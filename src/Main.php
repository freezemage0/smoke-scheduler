<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke;


use Freezemage\Config\ConfigFactory;
use Freezemage\Environment\Environment;
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

        $environment = new Environment();
        $factory = new ConfigFactory();
        $config = $factory->create($environment->get('CONFIG'));

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