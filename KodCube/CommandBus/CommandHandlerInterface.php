<?php
namespace KodCube\CommandBus;


interface CommandHandlerInterface
{
    public function __invoke(CommandInterface $command);
}