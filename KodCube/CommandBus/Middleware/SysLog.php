<?php
namespace GunnaPHP\CommandBus\Middleware;

use GunnaPHP\CommandBus\MiddlewareInterface;
use GunnaPHP\CommandBus\CommandInterface;
use GunnaPHP\CommandBus\CommandQueueInterface;
use GunnaPHP\CommandBus\HandlerNotFoundException;

class SysLog implements MiddlewareInterface
{
    
    protected $syslog = 'CommandBus';
    
   /**
    * Log Commands to SysLog
    *
    * @param CommandInterface $command 
    * @param Callable $next
    * @return CommandInterface
    * @throws HandlerNotFoundException
    */
    public function __invoke(CommandInterface $command,Callable $next = null)
    {
        openlog($this->syslog, LOG_PID | LOG_PERROR, LOG_LOCAL0);
        syslog(LOG_INFO,get_class($command));
        closelog();
        if (is_callable($next)) {
            return $next($command);
        }

        return $command;
    }
    
    
}