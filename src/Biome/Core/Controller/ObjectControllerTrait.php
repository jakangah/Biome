<?php

namespace Biome\Core\Controller;

use Biome\Core\Controller;
use Biome\Core\Collection;
use Biome\Core\ORM\Models;

trait ObjectControllerTrait
{
	public function getIndex() { }
	public function getCreate() { }
	public function getEdit($object_id)
	{
		$collection_name = $this->collectionName();
		$object_name = strtolower($this->objectName());
		$c = Collection::get($collection_name);
		$c->$object_name->sync($object_id);
	}

	public function getDelete($object_id)
	{
		$object_name = $this->objectName();
		$object = $object_name::get($object_id);

		if($object->delete())
		{
			$this->flash()->success($object_name . ' deleted!');
		}
		else
		{
			$this->flash()->error('Unable to delete the ' . $object_name . '!');
		}

		return $this->response()->redirect();
	}

	/**
	 * POST request for creating a new object.
	 */
	public function postCreate(Models $object)
	{
		$object_name = $this->objectName();
		if($object->save())
		{
			$this->flash()->success($object_name . ' created!');
		}
		else
		{
			$this->flash()->error('Unable to create the ' . $object_name . '!');
		}
		return $this->response()->redirect();
	}

	public function postEdit(Collection $collection)
	{
		$object_name = strtolower($this->objectName());
		if($collection->$object_name->save())
		{
			$this->flash()->success($object_name . ' updated!');
		}
		else
		{
			$this->flash()->error('Unable to update the ' . $object_name . '!', join(', ', $collection->$object_name->getErrors()));
		}

		return $this->response()->redirect();
	}

	public function postDelete(Models $object)
	{

	}

	public abstract function objectName();
	public abstract function collectionName();

}