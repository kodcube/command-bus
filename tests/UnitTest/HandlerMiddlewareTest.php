<?php
namespace KodCube\CommandBus\Test\UnitTest;

use KodCube\CommandBus\Middleware\Handler AS Middleware;
use KodCube\CommandBus\CommandInterface;
use KodCube\CommandBus\CommandHandlerInterface;
use KodCube\CommandBus\MissingHandlerException;
use KodCube\CommandBus\Test\Mock\TestCommand;
use KodCube\CommandBus\Test\Mock\TestHandler;
use KodCube\CommandBus\Test\Mock\TestOverride;
use KodCube\CommandBus\Test\Mock\TestMissingCommand;
use KodCube\Invoker\InvokerInterface;
use KodCube\Invoker\Invoker;
use TypeError;
use stdClass;
use Exception;

class HandlerMiddlewareTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Check Instantiation
     */
    public function testContructor()
    {
        $invoker = $this->getMock(InvokerInterface::class);
        
        $middleware = new Middleware([],$invoker);
        $this->assertTrue(true);
    }
    
    public function testContructorMissingInvoker()
    {
        $this->setExpectedException(TypeError::class);
        $middleware = new Middleware([]);
    }
    
    public function testContructorInvalidInvoker()
    {
        $this->setExpectedException(TypeError::class);
        $middleware = new Middleware([],new stdClass);
    }
    
    public function testContructorNoDependancies()
    {
        $this->setExpectedException(TypeError::class);
        $middleware = new Middleware();
    }
    
    /**
     * Check Invocation
     */
     public function testInvoke()
     {
        $invoker = $this->getMock(InvokerInterface::class);
        $command = $this->getMock(CommandInterface::class);
        $middleware = new Middleware([],$invoker);
        
        $phpunit = $this;
        $middleware($command,function ($passedCommand) use ($phpunit) {
            throw new Exception('Next should not be called');
            
        });
        $this->assertTrue(true);
     }

     public function testInvokeNoNext()
     {
        $invoker = $this->getMock(InvokerInterface::class);
        $command = $this->getMock(CommandInterface::class);
        $middleware = new Middleware([],$invoker);
        $middleware($command);
        $this->assertTrue(true);
        
     }

     public function testMissingCommand()
     {
        $this->setExpectedException(TypeError::class);
         
        $invoker = $this->getMock(InvokerInterface::class);
        
        $middleware = new Middleware([],$invoker);
        
        $middleware();
    }

    public function testInvalidCommand()
    {
        $this->setExpectedException(TypeError::class);
         
        $invoker = $this->getMock(InvokerInterface::class);
        
        $middleware = new Middleware([],$invoker);
        
        $middleware(new stdClass);
    }

    public function testAutoDetectHandler()
    {

        $invoker = $this->getMock(InvokerInterface::class);
        $invoker->method('__invoke')
                ->will($this->returnArgument(0));
        
        $middleware = new Middleware([],$invoker);
        
        $className = $middleware(new TestCommand);

        $this->assertEquals(TestHandler::class,$className);
    }


    public function testOverrideHandler()
    {

        $invoker = $this->getMock(InvokerInterface::class);
        $invoker->method('__invoke')
                ->will($this->returnArgument(0));
        
        $middleware = new Middleware([TestCommand::class => TestOverride::class],$invoker);
        
        $className = $middleware(new TestCommand);

        $this->assertEquals(TestOverride::class,$className);
    }


    public function testMissingHandler()
    {
        $this->setExpectedException(MissingHandlerException::class);
        
        $invoker = $this->getMock(InvokerInterface::class);

        $middleware = new Middleware([],$invoker);
        
        $className = $middleware(new TestMissingCommand);
    }

    public function testMissingOverrideHandler()
    {
        $this->setExpectedException(MissingHandlerException::class);
        
        $invoker = $this->getMock(InvokerInterface::class);
        
        $middleware = new Middleware([TestMissingCommand::class => 'TestMissingOverride'],$invoker);
        
        $className = $middleware(new TestMissingCommand);

    }


}
