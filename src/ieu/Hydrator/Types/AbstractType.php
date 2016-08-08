<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

abstract class AbstractType implements TypeInterface {

	final private function __construct(){}

	abstract public function getHydrationValue($value, HydrationContext $context);

	abstract public function getExtractionValue($value, ExtractionContext $context);

	public static function instance() {
		static $instance;

		if (!isset($instance)) {
			$instance = new static;
		}

		return $instance;
	}
}