<?php

namespace ieu\Hydrator\NamingStrategies;
use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Context\HydrationContext;

interface NamingStrategyInterface
{
    /**
     * Get the name to use for extraction
     *
     * @param  string                 $name
     * @param  ExtractionContext|null $context
     * @return string
     */
    public function getNameForExtraction($name, ExtractionContext $context = null);
    /**
     * Get the name to use for hydration
     *
     * @param  string                $name
     * @param  HydrationContext|null $context
     * @return string
     */
    public function getNameForHydration($name, HydrationContext $context = null);
}