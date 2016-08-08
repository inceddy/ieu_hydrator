<?php

namespace ieu\Hydrator;

use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Types\TypeInterface;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;

/**
 * This abstract hydrator provides a built-in support for filters and strategies. All
 * standards ZF3 hydrators extend this class
 */
abstract class AbstractHydrator implements HydratorInterface
{
    protected $properties = [];

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
    public function __construct()
    {
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

    public function setProperty($name, TypeInterface $type = null)
    {
        $this->properties[$name] = $type;

        return $this;
    }

    public function getProperty($name)
    {
        if (!array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException(sprintf('Property \'%s\' is not set.', $name));
        }

        return $this->properties[$name];
    }

    public function extractValue($name, $value, ExtractionContext $context = null)
    {
        if (null !== $type = $this->getProperty($name)) {
            return $type->getExtractionValue($value, $context);
        }

        return $value;
    }

    public function hydrateValue($name, $value, HydrationContext $context = null)
    {
        if (null !== $type = $this->getProperty($name)) {
            return $type->getHydrationValue($value, $context);
        }

        return $value;
    }
}
