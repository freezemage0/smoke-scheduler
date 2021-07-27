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
        $argumentList = $parser->getArgumentList();

        $environment = new Environment();
        $factory = new ConfigFactory();
        $config = $factory->create($environment->get('CONFIG'));

        $isDaemon = $argumentList->getByName('command')->getValue() == 'daemonize';

        if ($isDaemon) {
            $application = new Daemon($config);
        } else {
            $application = new Cli($config);
            $application->setArgumentList($argumentList);
        }

        $application->bootstrap();
        $application->run();
    }
}