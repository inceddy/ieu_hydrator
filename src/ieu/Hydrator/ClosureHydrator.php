<?php
namespace ieu\Hydrator;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;

final class ClosureHydrator extends AbstractHydrator
{
    private $className;

    private $doExtract;

    private $doHydrate;

    public function __construct(NamingStrategyInterface $namingStrategy = null)
    {
        $this->doExtract = function($object){
            static $context = [];

            $class = get_class($object);

            if (!isset($context[$class])) {
                $context[$class] = \Closure::bind(function($object) {
                    return get_object_vars($object);
                }, null, $class);
            }

            return $context[$class]($object);
        };

        $this->doHydrate = function($object, $property, $value){
            static $context = [];

            $class = get_class($object);

            if (!isset($context[$class])) {
                $context[$class] = \Closure::bind(function($object, $property, $value) {
                    $object->$property = $value;
                }, null, $class);
            }

            return $context[$class]($object, $property, $value);
        };

        parent::__construct($namingStrategy);
    }

    /**
     * {@inheritDoc}
     */
    
    public function extract($object)
    {
        $doExtract = $this->doExtract;

        $context = clone $this->extractionContext;
        $context->object = $object;

        $data = [];

        foreach($doExtract($object) as $propertyName => $value) {
            // Transform e.g. `camelCaseProperty` to `camel_case_property`
            $propertyNameExt = $this->namingStrategy ? $this->namingStrategy->getNameForExtraction($propertyName, $context) : $propertyName;

            $value = $this->extractValue($propertyName, $value, $context);
            $data[$propertyNameExt] = $value;
        }

        return $data;
    }


    /**
     * {@inheritDoc}
     */

    public function hydrate($object, array $data)
    {
        $doHydrate = $this->doHydrate;

        $context = clone $this->hydrationContext; // Performance trick, do not try to instantiate
        $context->object = $object;
        $context->data   = $data;
        
        foreach ($data as $propertyName => $value) {
            // Transform e.g. `camel_case_property` to `camelCaseProperty`
            $propertyName = $this->namingStrategy ? $this->namingStrategy->getNameForHydration($propertyName, $context) : $propertyName;
            $doHydrate($object, $propertyName, $this->hydrateValue($propertyName, $value, $context));
        }

        return $object;
    }
}