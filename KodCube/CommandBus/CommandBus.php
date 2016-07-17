<?php
namespace KodCube\CommandBus;

use KodCube\CommandBus\HandlerInterface;
use KodCube\Invoker\InvokerInterface;
use KodCube\MessageBus\QueueInterface;

class CommandBus implements CommandBusInterface
{
    protected $middleware = null;
    
    public function __construct(MiddlewareManagerInterface $manager)
    {
        $this->middleware = $this->buildMiddlewareChain($manager);
    }
    
   /**
    * Call/Dispatch passed Command 
    */
    
    public function __invoke(CommandInterface $command)
    {
        $middleware = $this->middleware;
        
        return $middleware($command);
        
    }
    
    protected function buildMiddlewareChain($manager)
    {
        $lastCallable = function () {};

        while ($manager->valid()) {

            $middleware = $manager->current();

            if (! $middleware instanceof MiddlewareInterface) {
                throw new InvalidMiddlewareException(get_class($middleware));
            }
            
            $lastCallable = function ($command) use ($middleware, $lastCallable) {
                return $middleware($command, $lastCallable);
            };

            $manager->next();

        }
        
        return $lastCallable;
    }
}
