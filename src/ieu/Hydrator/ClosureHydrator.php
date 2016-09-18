<?php
namespace ieu\Hydrator;
use ieu\Hydrator\Collections\PropertyCollection;
use ieu\Hydrator\Collections\ColumnCollection;
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

        // Get all properties from the object
        $dataRaw = $doExtract($object);
        // Group data
        $dataGrouped = $this->groupForExtraction($dataRaw);

        $context = clone $this->extractionContext;
        $context->object = $object;
        $context->raw = $dataRaw;

        $data = [];

        foreach($dataGrouped as $propertyName => $value) {
            $value = $this->extractValue($propertyName, $value, $context);

            // Ungoup column collections
            if ($value instanceof ColumnCollection) {
                $value->setName($propertyName);
                $value->setNamingStrategy($this->namingStrategy);
                // Property name to column name trnasformation happens inside the column collection.
                foreach ($value as $columnName => $value) {
                    $data[$columnName] = $value;
                }
            }

            else {
                $columnName = $this->namingStrategy->getNameForExtraction($propertyName, $context);
                $data[$columnName] = $value;
            }
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

        $dataGrouped = $this->groupForHydration($data);

        foreach ($dataGrouped as $columnName => $value) {
            // Transform e.g. `camel_case_property` to `camelCaseProperty`
            $propertyName = $this->namingStrategy->getNameForHydration($columnName, $context);
            $value = $this->hydrateValue($propertyName, $value, $context);

            // Ungroup property collection
            if ($value instanceof PropertyCollection) {
                $value->setNamingStrategy($this->namingStrategy);
                // Column name to property name trnasformation happens inside the column collection.
                foreach ($value as $propertyName => $value) {
                    $doHydrate($object, $propertyName, $value);
                }
            }
            else {
                $doHydrate($object, $propertyName, $value);
            }

        }

        return $object;
    }
}