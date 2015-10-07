<?php

namespace Biome;

use Biome\Core\URL;
use Biome\Core\Route;
use Biome\Core\Error;
use Biome\Core\HTTP\Request;
use Biome\Core\HTTP\Response;

class Biome
{
	protected static $directories	= NULL;
	protected static $_services		= array();
	protected static $_end_actions	= array();

	public static function start()
	{
		session_start();

		/* Initializing the Framework. */
		Error::init();

		/* Registering default services. */
		Biome::registerService('request', function() {
			return Request::createFromGlobals();
		});

		Biome::registerService('view', function() {
			return new \Biome\Core\View();
		});

		/* Starting. */
		$request = Biome::getService('request');

		/* Routing. */
		$router = new Route();
		$router->autoroute();

		/* Dispatch. */
		$dispatcher = $router->getDispatcher();
		$response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

		/* Send the response. */
		$response->send();

		/* Commit. */
		foreach(self::$_end_actions AS $action)
		{
			$action();
		}
	}

	public static function shell()
	{
		/* Initializing the Framework. */
		//Error::init();

		echo 'Biome Shell', PHP_EOL;
		echo '-----------', PHP_EOL;

		$dirs = self::getDirs('commands');
		foreach($dirs AS $dir)
		{
			$files = scandir($dir);
			foreach($files AS $file)
			{
				if($file[0] == '.')
				{
					continue;
				}

				include_once($dir . '/' . $file);

				$command = substr($file, 0, -4);
				echo substr($command, 0, -strlen('Command')), PHP_EOL;

				$command::listCommands();
			}
		}

		$cmd = new $command();
		$cmd->showCreateTable();

		/* Commit. */
		foreach(self::$_end_actions AS $action)
		{
			$action();
		}
	}

	private static function initDirs()
	{
		if(self::$directories !== NULL)
		{
			return TRUE;
		}

		self::$directories = array();

		$dirs = array(
			'controllers'	=> __DIR__ . '/../app/controllers/',
			'models'		=> __DIR__ . '/../app/models/',
			'views'			=> __DIR__ . '/../app/views/',
			'components'	=> __DIR__ . '/../app/components/',
			'collections'	=> __DIR__ . '/../app/collections/',
			'commands'		=> __DIR__ . '/../app/commands/',
		);
		self::registerDirs($dirs);
		return TRUE;
	}

	public static function registerDirs(array $dirs)
	{
		self::initDirs();
		foreach($dirs AS $type => $dir)
		{
			self::$directories[$type][$dir] = $dir;
		}
		return TRUE;
	}

	public static function getDirs($type)
	{
		return self::$directories[$type];
	}

	public static function registerAlias(array $alias)
	{
		/**
		 * TODO: Replace this ugly and unsecure things by a better autoloading.
		 */
		foreach($alias AS $a => $c)
		{
			eval('class ' . $a . ' extends ' . $c . ' {};');
		}
	}

	public static function registerService($service_name, $callable)
	{
		if(!is_callable($callable))
		{
			throw new \Exception('Unable to register a service on a non callable!');
		}
		self::$_services[$service_name]['function'] = $callable;
		return TRUE;
	}

	public static function getService($service_name)
	{
		if(!isset(self::$_services[$service_name]))
		{
			throw new \Exception('Service undefined ' . $service_name);
		}

		if(!isset(self::$_services[$service_name]['instance']))
		{
			$func = self::$_services[$service_name]['function'];
			self::$_services[$service_name]['instance'] = $func();
		}

		return self::$_services[$service_name]['instance'];
	}

	public static function setFinal($action)
	{
		if(!is_callable($action))
		{
			throw new \Exception('Unable to set final action on a non callable!');
		}
		self::$_end_actions[] = $action;
		return TRUE;
	}
}
