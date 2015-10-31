<?php

use Biome\Biome;
use Biome\Core\Command\AbstractCommand;
use Biome\Core\ORM\ObjectLoader;
use Biome\Core\ORM\Inspector\SQLModelInspector;

class DatabaseCommand extends AbstractCommand
{
	public function showCreateTable()
	{
		$objects = ObjectLoader::getObjects();

		/**
		 * For each models, generate create table.
		 */
		foreach($objects AS $object_name => $object)
		{
			$sql_inspector = new SQLModelInspector();
			$object->inspectModel($sql_inspector);

			$query = $sql_inspector->generate();
			echo $query, PHP_EOL;
		}
	}
}
