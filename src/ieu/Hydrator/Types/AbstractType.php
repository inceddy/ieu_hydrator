<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

abstract class AbstractType implements TypeInterface {

	/**
	 * {@inheritDoc}
	 */
	
	abstract public function getHydrationValue($value, HydrationContext $context);


	/**
	 * {@inheritDoc}
	 */
	
	abstract public function getExtractionValue($value, ExtractionContext $context);


	/**
	 * Factory method for use as singleton
	 *
	 * @return TypeInterface
	 *    The instance of the current type
	 */
	
	public static function instance() {
		static $instance;

		if (!isset($instance)) {
			$instance = new static;
		}

		return $instance;
	}
}