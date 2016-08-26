<?php

namespace ieu\Hydrator;

use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Types\TypeInterface;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;


abstract class AbstractHydrator implements HydratorInterface
{
    protected $types = [];

    protected $namingStrategy = null;

    /**
     * @var ExtractionContext
     */
    protected $extractionContext;

    /**
     * @var HydrationContext
     */
    protected $hydrationContext;

    /**
     * Constructor
     */
    public function __construct(NamingStrategyInterface $namingStrategy = null)
    {
        $this->namingStrategy = $namingStrategy;
        
        $this->extractionContext = new ExtractionContext();
        $this->hydrationContext  = new HydrationContext();
    }

    public function setNamingStrategy(NamingStrategyInterface $namingStrategy = null)
    {
        $this->namingStrategy = $namingStrategy;

        return $this;
    }

    public function getNamingStrategy()
    {
        return $this->namingStrategy;
    }

    public function setType($name, TypeInterface $type = null)
    {
        $this->types[$name] = $type;

        return $this;
    }

    public function getType($property)
    {
        return isset($this->types[$property]) ? $this->types[$property] : null;
    }


    /**
     * {@inheritDoc}
     */
    
    public function extractValue($name, $value, ExtractionContext $context = null)
    {
        if (null !== $type = $this->getType($name)) {
            return $type->getExtractionValue($value, $context);
        }

        return $value;
    }


    /**
     * {@inheritDoc}
     */

    public function hydrateValue($name, $value, HydrationContext $context = null)
    {
        if (null !== $type = $this->getType($name)) {
            return $type->getHydrationValue($value, $context);
        }

        return $value;
    }
}
