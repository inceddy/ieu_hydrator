<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

class DateTimeType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */

	public function getHydrationValue($value, HydrationContext $context)
	{
		return !!$value ? (new \DateTime())->setTimestamp($value) : null;
	}


	/**
	 * {@inheritDoc}
	 */

	public function getExtractionValue($value, ExtractionContext $context)
	{
		return !!$value ? $value->getTimestamp() : null;
	}
}