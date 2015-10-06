<?php

namespace Biome\Component;

use Biome\Core\View\Component;

class AComponent extends Component
{
	public function getURL()
	{
		$controller = $this->attributes['controller'];
		$action = $this->attributes['action'];
		return \URL::fromRoute($controller, $action);
	}
}