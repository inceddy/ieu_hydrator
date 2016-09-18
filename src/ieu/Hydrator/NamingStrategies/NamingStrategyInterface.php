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


    /**
     * Concats multiple names conforming 
     * the naming strategy for extraction.
     *
     * @param  array $pieces 
     *    The names to concat
     *
     * @return string
     *    The concated names
     */
    
    public function concatColumnNames(... $names);


    /**
     * Concats multiple names conforming 
     * the naming strategy for hydration.
     *
     * @param  array $pieces 
     *    The names to concat
     *
     * @return string
     *    The concated names
     */

    public function concatPropertyNames(... $names);
}