<?php

namespace ieu\Hydrator;
use ieu\Hydrator\NamingStrategies\NamingStrategyInterface;

class Group {

	const EXTRACTION = 0b01;
	const HYDRATION  = 0b10;

	protected $type;

	protected $map;

	protected $mapFlipped;

	public function __construct(NamingStrategyInterface $nameingStrategy, array $names, $type = self::EXTRACTION | self::HYDRATION)
	{
		// set type
		$this->type = $type;

		// build map
		$this->map = [];
		foreach ($names as $name) {
			if (is_array($name)) {
				$this->map[$name[1]] = $nameingStrategy->concatNamesForExtraction(...$name);
			}
			else {
				$this->map[$name] = $name;
			}
		}

		$this->mapFlipped = array_flip($this->map);
	}

	public function getType()
	{
		return $this->type;
	}

	public function getLocalName($globalName)
	{
		return $this->mapFlipped[$globalName];
	}

	public function getGlobalName($localName) 
	{
		return $this->map[$localName];
	}

	public function hasGlobalName($globalName) {
		return in_array($globalName, $this->map);
	}

	public function hasLocalName($localName)
	{
		return array_key_exists($localName, $this->map);
	}
}