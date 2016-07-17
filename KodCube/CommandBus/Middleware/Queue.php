<?php
namespace GunnaPHP\CommandBus\Middleware;

use GunnaPHP\CommandBus\MiddlewareInterface;
use GunnaPHP\CommandBus\CommandInterface;
use GunnaPHP\CommandBus\CommandQueueInterface;
use GunnaPHP\CommandBus\HandlerNotFoundException;

class Queue implements MiddlewareInterface
{
    public function __construct( )
    {

    }
    
   /**
    * Detect Handler for a Passed Command
    *
    * @param CommandInterface $command 
    * @return String
    * @throws HandlerNotFoundException
    */
    public function __invoke(CommandInterface $command,Callable $next = null)
    {
        if ( $command instanceOf CommandQueueInterface) {
            echo 'Queue Command '.get_class($command).PHP_EOL;
            return $command;
        }
        if (is_callable($next)) {
            return $next($command);
        }
        return $command;
    }
    
    
}