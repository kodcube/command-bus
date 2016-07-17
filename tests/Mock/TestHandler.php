<?php
namespace KodCube\CommandBus\Test\Mock;

use KodCube\CommandBus\CommandInterface;
use KodCube\CommandBus\CommandHandlerInterface;

class TestHandler implements CommandHandlerInterface
{
    public function __invoke(CommandInterface $command) {
        return __CLASS__;
    }
}