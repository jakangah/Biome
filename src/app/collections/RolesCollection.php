<?php

use Biome\Core\Collection\RequestCollection;

class RolesCollection extends RequestCollection
{
	protected $map = array(
		'role' => 'Role',
		'roles' => array()
	);

	public function getRoles()
	{
		if(empty($this->roles))
		{
			\Biome\Core\ORM\ObjectLoader::load('role');
			$this->roles = Role::all();
		}
		return $this->roles;
	}
}
