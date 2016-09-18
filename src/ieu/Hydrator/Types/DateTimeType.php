<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Collections\ColumnCollection;
use ieu\Hydrator\Collections\PropertyCollection;
use ieu\Hydrator\Group;

class DateTimeType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */

	public function getHydrationValue($value, HydrationContext $context)
	{
		if (!$value) {
			return null;
		}

		return !!$value ? (new \DateTime())->setTimestamp($value) : null;
	}


	/**
	 * {@inheritDoc}
	 */

	public function getExtractionValue($value, ExtractionContext $context)
	{
		if (!$value) {
			return null;
		}
		
		return !!$value ? $value->getTimestamp() : null;
	}

	public function getGroupInstructions()
	{
		return [['.timeStamp', '.timeZone'], Group::HYDRATION];
	}
}