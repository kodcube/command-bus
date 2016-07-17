<?php
namespace KodCube\CommandBus\Middleware;

use KodCube\CommandBus\MiddlewareInterface;
use KodCube\CommandBus\CommandInterface;
use KodCube\CommandBus\CommandAsyncInterface;
use KodCube\CommandBus\HandlerNotFoundException;

class MessageBus implements MiddlewareInterface
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
        if ( $command instanceOf CommandAsyncInterface) {
            return $command;
        }
        if (is_callable($next)) {
            return $next($command);
        }
        return $command;
    }
    
    
}