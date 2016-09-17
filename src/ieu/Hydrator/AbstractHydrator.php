<?php

namespace ieu\Hydrator;

use ieu\Hydrator\Context\ExtractionContext;
use ieu\Hydrator\Context\HydrationContext;
use ieu\Hydrator\Types\TypeInterface;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;

abstract class AbstractHydrator implements HydratorInterface
{
    private static $defaultNamingStrategyClassName = UnderscoreNamingStrategy::CLASS;

    final public static function setDefaultNamingStrategy($strategy)
    {
        switch (true) {
            case is_object($strategy) && $strategy instanceof NamingStrategyInterface:
                self::$defaultNamingStrategyClassName = get_class($strategy);
                break;

            case is_string($strategy) && class_exists($strategy) && in_array(NamingStrategyInterface::CLASS, class_implements($strategy)):
                self::$defaultNamingStrategyClassName = $strategy;
                break;

            default:
                throw \InvalidArgumentException('Given parameter is not a valid NamingStrategy.');
        }        
    }

    protected $types = [];

    protected $groups = [];

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
        $this->namingStrategy = $namingStrategy ?: new self::$defaultNamingStrategyClassName;
        
        $this->extractionContext = new ExtractionContext;
        $this->hydrationContext  = new HydrationContext;

        $this->propertyCollection = new Collections\PropertyCollection($this->namingStrategy);
        $this->columnCollection = new Collections\ColumnCollection($this->namingStrategy);
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

    public function getType($name)
    {
        return $this->hasType($name) ? $this->types[$name] : null;
    }

    public function hasType($name)
    {
        return isset($this->types[$name]);
    }

    /**
     * Sets a group definition.
     * Property names beginning with a dot will be concated 
     * with the group name.
     *
     * Usage:
     * ```
     * $hydrator->setGroup('createdAt', ['.timeStamp', '.timeZone'], Group::HYDRATION);
     * ```
     * The columns named `created_at_time_stamp` and `created_at_time_zone` will 
     * be grouped in a `\ieu\Hydrator\Collections\ColumnCollection` under the name `created_at`.
     *
     * ```
     * $hydrator->setGroup('createdAt', ['timeStamp', 'timeZone'], Group::HYDRATION);
     * ```
     * The columns named `time_stamp` and `time_zone` will 
     * be grouped in a `\ieu\Hydrator\Collections\ColumnCollection` under the name `created_at`.
     *
     * In both cases you can access these values in an Type like
     * ```
     * function getValueForHydration($value, $context) 
     * {
     *     $timestamp = $value['timeStamp']; // or $value->timeStamp
     *     $timezone  = $value['timeZone'];  // or $value->timeZone;
     *
     *     $date = new DateTime($timestamp);
     *     $date->setTimeZone(new DateTimeZone($timezone));
     *
     *     return $date;
     * }
     * ```
     * 
     * @param string $name
     *    The group name
     * 
     * @param array<string>  $propertyNames 
     *    The property/column names to group together (in PHP case).
     *    Names beginning with a dot will be concatinated with the group name.
     *    
     * @param int $type
     *    If this group will be active on hydration
     *    AND / OR extraction.
     *
     * @return self
     * 
     */
    
    public function setGroup($name, array $propertyNames, $type = Group::EXTRACTION | Group::HYDRATION)
    {
        $propertyNames = array_map(function($propertyName) use ($name) {
            return $propertyName[0] == '.' ? [$name, substr($propertyName, 1)] : $propertyName;
        }, $propertyNames);

        $this->groups[$name] = new Group($this->namingStrategy, $propertyNames, $type);
        return $this;
    }

    public function getGroup($name)
    {
        return $this->hasGroup($name) ? $this->groups[$name] : null;
    }

    public function hasGroup($name)
    {
        return isset($this->groups[$name]);
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



    protected function groupForExtraction(array $data)
    {
        $collection = clone $this->propertyCollection; 

        // @TODO: Use ieu\Types\Arr -> $groups = $this->groups->filter('AbstractHydrator::GROUP_EXTRACTION & $v->getType()');
        $groups = array_filter($this->groups, function($group)  {
            return Group::EXTRACTION & $group->getType();
        });

        // Nothing to group
        if (empty($groups)) {
            return $data;
        }

        foreach($groups as $groupName => $group) {
            $collection->setName($groupName);

            $dataGroup = $dataRest = [];

            foreach ($data as $globalName => $value) {
                
                if ($group->hasGlobalName($globalName)) {
                    $dataGroup[$group->getLocalName($globalName)] = $value;

                }
                else {
                    $dataRest[$globalName] = $value;
                }
            }

            // Set new $propertyName => $value array 
            $collection->exchangeArray($dataGroup);
            
            $data = $dataRest;
            $data[$groupName] = $collection;
        }

        return $data;  
    }

    /**
     * 
     *
     * @param  array  $data [description]
     *
     * @return [type]       [description]
     */
    
    protected function groupForHydration(array $data)
    {
        $collection = clone $this->columnCollection; 

        // @TODO: Use ieu\Types\Arr -> $groups = $this->groups->filter('AbstractHydrator::GROUP_EXTRACTION & $v->getType()');
        $groups = array_filter($this->groups, function($group)  {
            return Group::HYDRATION & $group->getType();
        });

        // Nothing to group
        if (empty($groups)) {
            return $data;
        }

        foreach($groups as $groupName => $group) {
            $columnGroupName = $this->namingStrategy->getNameForExtraction($groupName);
            $collection->setName($groupName);

            $dataGroup = $dataRest = [];

            foreach ($data as $columnName => $value) {
                $globalName = $this->namingStrategy->getNameForHydration($columnName);

                if ($group->hasGlobalName($globalName)) {
                    $dataGroup[$group->getLocalName($globalName)] = $value;
                }
                else {
                    $dataRest[$columnName] = $value;
                }
            }

            // Set new $propertyName => $value array 
            $collection->exchangeArray($dataGroup);
            
            $data = $dataRest;
            $data[$columnGroupName] = $collection;
        }

        return $data; 
    }
}