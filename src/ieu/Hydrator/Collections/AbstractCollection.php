<?php

namespace ieu\Hydrator\Collections;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;

abstract class AbstractCollection implements \IteratorAggregate, \ArrayAccess {

	protected $data;

	private $name;

	protected $namingStrategy;

	public function __construct($data = [], NamingStrategyInterface $namingStrategy = null)
	{
		$this->namingStrategy = $namingStrategy;
		$this->data = $data;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setNamingStrategy(NamingStrategyInterface $namingStrategy)
	{
		$this->namingStrategy = $namingStrategy;
		return $this;
	}

	public function getNamingStrategy()
	{
		return $this->namingStrategy;
	}

	public function exchangeArray(array $data)
	{
		$this->data = $data;
		return $this;
	}

	abstract protected function getIteratorArray();

    public function __get ($key) 
    {
    	return $this->data[$key];
    }

    public function __set($key,$value) 
    {
        $this->data[$key] = $value;
    }

    public function __isset ($key) 
    {
        return isset($this->data[$key]);
    }

    public function __unset($key) {
        unset($this->data[$key]);
    }

    public function offsetSet($offset,$value) 
    {
        if (null === $offset) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset) 
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) 
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    public function offsetGet($offset) 
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }
	
	public function getIterator()
	{
		return new \ArrayIterator($this->getIteratorArray());
	}
}