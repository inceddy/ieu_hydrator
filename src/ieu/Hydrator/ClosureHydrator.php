<?php
namespace ieu\Hydrator;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;

final class ClosureHydrator extends AbstractHydrator
{
    private $className;

    private $doExtract;

    private $doHydrate;

    public function __construct()
    {
        $this->doExtract = function($object, $property){
            static $context = [];

            $class = get_class($object);

            if (!isset($context[$class])) {
                $context[$class] = \Closure::bind(function($object, $property) {
                    return isset($object->$property) ? $object->$property : null;
                }, null, $class);
            }

            return $context[$class]($object, $property);
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

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    
    public function extract($object)
    {
        $doExtract = $this->doExtract;

        $context = clone $this->extractionContext; // Performance trick, do not try to instantiate
        $context->object = $object;

        $data = [];
        
        foreach ($this->properties as $propertyName => $type) {

            $value = $this->extractValue(
                $propertyName,
                $doExtract($object, $propertyName),
                $context
            );

            $propertyName = $this->namingStrategy ? $this->namingStrategy->getNameForExtraction($propertyName, $context) : $propertyName;

            $data[$propertyName] = $value;
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

            $propertyName = $this->namingStrategy ? $this->namingStrategy->getNameForHydration($propertyName, $context) : $propertyName;

            $doHydrate($object, $propertyName, $this->hydrateValue(
                $propertyName,
                $value,
                $context
            ));
        }

        return $object;
    }
}