<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Collections\ColumnCollection;
use ieu\Hydrator\Collections\PropertyCollection;

class ClosureType extends AbstractType {

	private $onHydration;

	private $onExtraction;

	public function __construct(\Closure $onHydration, \Closure $onExtraction)
	{
		$this->onHydration = $onHydration;
		$this->onExtraction = $onExtraction;
	}

	/**
	 * {@inheritDoc}
	 */

	public function getHydrationValue($value, HydrationContext $context)
	{
		$onHydration = $this->onHydration;
		return $onHydration($value, $context);
	}


	/**
	 * {@inheritDoc}
	 */

	public function getExtractionValue($value, ExtractionContext $context)
	{
		$onExtraction = $this->onExtraction;
		return $onExtraction($value, $context);
	}
}