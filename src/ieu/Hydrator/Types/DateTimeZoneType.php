<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Collections\ColumnCollection;
use ieu\Hydrator\Collections\PropertyCollection;
use ieu\Hydrator\Group;

class DateTimeZoneType extends AbstractType {

	/**
	 * {@inheritDoc}
	 */

	public function getHydrationValue($value, HydrationContext $context)
	{
		if ($value['timeZone'] == null) {
			return null;
		}

		$dateTime = new \DateTime();
		$dateTimeZone = new \DateTimeZone($value['timeZone']);
		$dateTime->setTimestamp($value['timeStamp']);
		$dateTime->setTimeZone($dateTimeZone);

		return $dateTime;
	}


	/**
	 * {@inheritDoc}
	 */

	public function getExtractionValue($value, ExtractionContext $context)
	{
		if (!$value) {
			return new ColumnCollection(['.timeStamp' => null, 'timeZone' => null]);
		}
		
		return new ColumnCollection(['.timeStamp' => $value->getTimestamp(), 'timeZone' => (string)$value->getTimezone()]);
	}


	/**
	 * {@inheritDoc}
	 */

	public function getGroupDefinition()
	{
		return [['.timeStamp', '.timeZone'], Group::HYDRATION];
	}
}