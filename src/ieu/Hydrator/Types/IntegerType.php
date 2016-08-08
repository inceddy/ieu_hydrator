<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

class IntegerType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */
	
	public function getHydrationValue($value, HydrationContext $context)
	{
		return (integer) $value;
	}


	/**
	 * {@inheritDoc}
	 */

	public function getExtractionValue($value, ExtractionContext $context)
	{
		return $value;
	}
}