<?php
namespace KodCube\CommandBus;

interface CommandBusInterface
{
    public function __invoke(CommandInterface $command);
}