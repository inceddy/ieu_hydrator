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
	 * Returns a group definiton for this type
	 * or `NULL` if this type does not group anything or 
	 * will be manualy grouped.
	 *
	 * @return array
	 */
	
	public function getGroupDefinition()
	{
		/**
		 * Example:
		 * return [['.lat', '.lng'], ieu\Hydrator\Group::HYDRATION];
		 */
		
		return null;
	}


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