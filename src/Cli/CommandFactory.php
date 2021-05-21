<?php
/** @author Demyan Seleznev <seleznev@intervolga.ru> */


namespace Freezemage\Smoke\Cli;


use Freezemage\Smoke\Cli\Command\Command;
use Freezemage\Smoke\Cli\Command\CommandInterface;
use Freezemage\Smoke\Cli\Command\ListCommand;
use Freezemage\Smoke\Cli\Command\PauseCommand;
use Freezemage\Smoke\Cli\Command\PersistCommand;
use Freezemage\Smoke\Cli\Command\RestoreCommand;
use Freezemage\Smoke\Cli\Command\ResumeCommand;
use Freezemage\Smoke\Cli\Command\StartCommand;
use Freezemage\Smoke\Cli\Command\StatusCommand;
use Freezemage\Smoke\Cli\Command\StopCommand;
use Freezemage\Smoke\Cli\Command\UnknownCommand;
use Freezemage\Smoke\Cli\Command\UpdateCommand;
use Freezemage\Smoke\Scheduler;


class CommandFactory {
    protected $scheduler;
    protected $commands;

    public function __construct(Scheduler $scheduler) {
        $this->scheduler = $scheduler;
        $this->buildDefaultCommands();
    }

    public function getCommand(string $name): ?CommandInterface {
        foreach ($this->commands as $command) {
            if ($command->canProcess($name)) {
                return $command;
            }
        }

        return new UnknownCommand();
    }

    public function addCommand(CommandInterface $command): void {
        $this->commands[] = $command;
    }

    protected function buildDefaultCommands(): void {
        $this->commands = array(
                new StartCommand($this->scheduler),
                new StopCommand($this->scheduler),
                new ListCommand($this->scheduler),
                new PauseCommand($this->scheduler),
                new ResumeCommand($this->scheduler),
                new StatusCommand($this->scheduler),
                new UpdateCommand($this->scheduler),
                new PersistCommand($this->scheduler),
                new RestoreCommand($this->scheduler)
        );
    }
}