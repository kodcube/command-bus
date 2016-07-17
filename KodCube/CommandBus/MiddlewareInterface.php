<?php
namespace KodCube\CommandBus;


interface MiddlewareInterface
{
    
    public function __invoke(CommandInterface $command,Callable $next=null);
}