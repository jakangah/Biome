<?php

namespace Biome\Core\ORM\Field;

use Biome\Core\ORM\AbstractField;
use Biome\Core\ORM\Converter\DateTimeConverter;

class DateField extends AbstractField
{
	public function __construct()
	{
	//	$this->setConverter(new DateTimeConverter());
	}
}
