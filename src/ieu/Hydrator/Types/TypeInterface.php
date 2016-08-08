<?php

namespace ieu\Hydrator\Types;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Context\ExtractionContext;

interface TypeInterface {

	public function getHydrationValue($value, HydrationContext $context);

	public function getExtractionValue($value, ExtractionContext $context);
}