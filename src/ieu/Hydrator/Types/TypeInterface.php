<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

interface TypeInterface {

	/**
	 * Transforms the value comming from the database
	 * to the value used in the entity.
	 *
	 * @param  mixed    $value
	 *    The raw database value to decode
	 * @param  HydrationContext $context
	 *    The context of hydration
	 *
	 * @return mixed
	 *    The decoded entity value
	 */

	public function getHydrationValue($value, HydrationContext $context);


	/**
	 * Transforms the entity value to the
	 * raw/encoded value to store in the
	 * database.
	 *
	 * @param  mixed $value
	 *    The entity value to encode
	 * @param  ExtractionContext $context
	 *    The context of extraction
	 *
	 * @return mixed
	 *    The raw/encoded value
	 */
	
	public function getExtractionValue($value, ExtractionContext $context);
}