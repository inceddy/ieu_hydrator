<?php

namespace ieu\Hydrator\NamingStrategies;
use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Context\HydrationContext;

final class UnderscoreNamingStrategy implements NamingStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function getNameForExtraction($name, ExtractionContext $context = null)
    {
        return strtolower(preg_replace('/\B([A-Z])/', '_$0', $name));
    }
    /**
     * {@inheritDoc}
     */
    public function getNameForHydration($name, HydrationContext $context = null)
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($name)))));
    }
}