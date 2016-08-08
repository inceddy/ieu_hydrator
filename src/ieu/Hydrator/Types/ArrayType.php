<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

class ArrayType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */

	public function getHydrationValue($value, HydrationContext $context)
	{
		return json_decode($value, true);
	}


	/**
	 * {@inheritDoc}
	 */

	public function getExtractionValue($value, ExtractionContext $context)
	{
		if (!is_array($value)) {
			throw \InvalidArgumentException(sprintf('Parameter value must be type of \'array\' but \'%s\' given.', gettype($value)));
		}

		return json_encode($value);
	}
}