<?php
namespace KodCube\CommandBus;

use Iterator;
use Interop\Container\ContainerInterface;

class MiddlewareManager implements MiddlewareManagerInterface,Iterator
{
    protected $container  = [];
    protected $middleware = [];
    
    
    public function __construct(array $middleware = [],ContainerInterface $container)
    {
        $this->container = $container;
        
        
        if (empty($middleware)) {
            $this->middleware = [
               $container->get(__NAMESPACE__.'\\Middleware\\Handler')
            ];
            return;
        }
        
        $this->middleware = array_reverse($middleware);
    }
    
    public function __get($key)
    {
        if (isset($this->middleware[$key])) return $this->middleware[$key];
        return NULL;
    }

    public function __isset($key)
    {
        return isset($this->middleware[$key]);
    }

    public function rewind()
    {
        reset($this->middleware);
    }

    public function current()
    {
        $middleware = current($this->middleware);
        if (is_string($middleware)) {
            $middleware = $this->container->get($middleware);
            $this->middleware[$this->key()] = $middleware;
        }
        return $middleware;
    }

    public function key()
    {
        return key($this->middleware);
    }

    public function next()
    {
        
        return next($this->middleware);
    }

    public function valid()
    {
        return (key($this->middleware) !== NULL);
    }

    public function count()
    {
        return sizeof($this->middleware);
    }

    public function jsonSerialize()
    {
        return array_values($this->middleware);
    }
}