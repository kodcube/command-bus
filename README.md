# Middleware Based Command Bus

A simple command bus that uses the middleware pattern for processing commands.

It has been designed to leaverage Dependency Injection with Autowiring support. All of it's dependencies and be configured & overridden by a dependency injection container

### Main Features 
* All Dependancies are injected at construction
* All Dependencies are based on interfaces
* Route Commands differently based on custom middleware
* Use simple arrays for middleware configuration
* Uses Dependency Injection Container when loading middleware
* Uses Dependency Injection Container when loading handlers

### Limitations
* Does not inject dependencies for methods other than __constructor 
* Does not inject dependencies for setters

### Requirements
* PHP 7
* Container-Interop - Dependancy Injection Container
* GunnaPHP\Invoker

### Optional
* KodCube\DependencyInjection - Dependancy Injection Container
* KodCube\MessageBus - Dispatching commands to background processes


## Usage

* [Create Command Bus](#create-command-bus)
* [Dispatch Command](#dispatch-command)
* [Override Command Handler](#override-command-handler)
* [Multiple Handlers](#multiple-handlers)



### Create Command Bus

This will create a command bus with the default handler detection

``` PHP
$commandbus = new KodCube\CommandBus\CommandBus(
			        new KodCube\CommandBus\MiddlewareManager(
			        	new KodCube\DependencyInjection\Container()
			        )
              );
``` 

or using dependancy injection container

``` PHP
DI Config
[
	MiddlewareManagerInterface::class => MiddlewareManager::class,
	ContainerInterface::class => Container::class
]

$commandbus = $di->get(KodCube\CommandBus\CommandBus::class);
``` 


### Dispatch Command

Assuming you have the following command and handler

Command Class: MyCommand 

Handler Class: MyHandler


``` PHP
$command = new MyCommand();

$response = $commandbus($command);
or 
$response = $commandbus->handle($command);
```
is the equivalant to 

``` PHP
$command = new MyCommand();
$handler = new MyHandler();

$response = $handler($command);
```
### Override Command Handler

The default handler middleware allows you to override which handler is responsible for handling the command

``` PHP
$commandbus = new KodCube\CommandBus\CommandBus(
			        new KodCube\CommandBus\MiddlewareManager(
			        	new KodCube\DependencyInjection\Container(),
			        	new KodCube\CommandBus\Middleware\Handler(
			        		[
			        			MyCommand::class => MyNewHandler::class
			        		]
			        	)
			        )
              );
``` 
is the equivalant to 

``` PHP
$command = new MyCommand();
$handler = new MyNewHandler();

$response = $handler($command);
```

or using dependancy injection container

``` PHP
DI Config
[
	MiddlewareManagerInterface::class => MiddlewareManager::class,
	ContainerInterface::class => Container::class
	Handler::class => [
		[
			MyCommand::class => MyNewHandler::class
		]
	]
]

$commandbus = $di->get(KodCube\CommandBus\CommandBus::class);
``` 

### Multiple Middlerware Handlers

Using the middleware capablies we can easily add multiple command handlers, to process some commands differently from others.

In this example we add a Queueable Middleware Handler, that will look for commands that implement a *CommandQueueInterface::class*.

Theses commands will be routed to a message bus for processing on a background process, were as all other command will be handled by the final middleware handler.

``` PHP
$commandbus = new KodCube\CommandBus\CommandBus(
                new KodCube\CommandBus\MiddlewareManager(
                    [
                      KodCube\CommandBus\Middleware\Queue::class,
                      KodCube\CommandBus\Middleware\Handler::class
                    ],
                    new KodCube\DependencyInjection\Container(), 
			      )
              );
``` 
or using dependancy injection container

``` PHP
DI Config
[
	MiddlewareManagerInterface::class => MiddlewareManager::class,
	MiddlewareManager::class => [
		Middleware\Queue::class,
		Middleware\Handler::class
	],
	ContainerInterface::class => Container::class,
	Handler::class => [
		[
			MyCommand::class => MyNewHandler::class
		]
	]
]

$commandbus = $di->get(KodCube\CommandBus\CommandBus::class);
``` 

