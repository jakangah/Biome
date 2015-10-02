<?php

namespace Biome\Component;

use Biome\Core\View\Component;

class Navbar extends Component
{
	public function render()
	{
		$forms = $this->getChildren('form', -1);

		foreach($forms AS $form)
		{
			$form->addClasses('navbar-form');
		}

		return parent::render();
	}
}
