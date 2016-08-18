<?php

namespace ieu\Hydrator;

interface HydratorInterface {

	/**
	 * Sets the properties of the object
	 * with the values given.
	 *
	 * @param  object $object 
	 *    The object to hydrate
	 * @param  array  $data
	 *    The propertyname value pairs to set
	 *
	 * @return object
	 *    The hydrated object
	 */
	
	public function hydrate($object, array $data);


	/**
	 * Extracts all properties of the object as
	 * assoc array with the propertynames as keys.
	 *
	 * @param  object $object
	 *    The object to extract
	 *
	 * @return array
	 *    The propertyname value pairs
	 */
	
	public function extract($object);
}