<?php

namespace Biome\Core\ORM;

class ObjectLoader
{
	public static function load($object_name)
	{

		/**
		 * TODO: Replace this dirty code by an autoload.
		 */
		$dirs = array(
			__DIR__ . '/../../../app/models',
			\Biome\Biome::getDir('models')
		);

		foreach($dirs AS $d)
		{
			if(!file_exists($d))
			{
				continue;
			}

			$files = scandir($d);
			foreach($files AS $f)
			{
				if($f[0] == '.')
				{
					continue;
				}
				include_once($d . '/' . $f);
			}
		}

		$object = new $object_name();

		if(!$object instanceof ObjectInterface)
		{
			throw new \Exception('The models ' . $object_name . ' doesn\'t implement the ObjectInterface!');
		}

		$object->fields();

		return $object;
	}
}