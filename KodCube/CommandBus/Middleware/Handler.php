<?php
namespace GunnaPHP\CommandBus\Middleware;

use GunnaPHP\CommandBus\MiddlewareInterface;
use GunnaPHP\CommandBus\CommandInterface;
use GunnaPHP\CommandBus\MissingHandlerException;
use GunnaPHP\Invoker\InvokerInterface;

class Handler implements MiddlewareInterface
{
    protected $invoker    = [];
    protected $overrides  = [];
    
    public function __construct(array $overrides=[],InvokerInterface $invoker)
    {
        $this->invoker    = $invoker;
        $this->overrides  = $overrides;
    }
    
   /**
    * Detect Handler for a Passed Command
    *
    * 1) Checks for Config Override 
    * 2) Attempts Auto Detection based on class name
    *
    * @param CommandInterface $command 
    * @return String
    * @throws HandlerNotFoundException
    */
    
    public function __invoke(CommandInterface $command,Callable $next=null)
    {
        $commandClassName = get_class($command);
        
        // Check if Handler Overtide Exists
        if (isset($this->overrides[$commandClassName])) {
            // Use Override Handler
            $handlerClassName = $this->overrides[$commandClassName];
        } else {
            // Auto Detect Handler
            $handlerClassName = preg_replace('/Command$/','Handler',$commandClassName);
        }

        if ( ! class_exists($handlerClassName,true)) {
            throw new MissingHandlerException('Handler for '.$commandClassName.' Not Found');
        }
        
        $invoker = $this->invoker;
        return $invoker($handlerClassName,[$command]);
    }
    
    
}