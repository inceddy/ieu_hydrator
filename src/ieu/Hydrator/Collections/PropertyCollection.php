<?php

namespace ieu\Hydrator\Collections;
class PropertyCollection extends AbstractCollection {
	protected function getIteratorArray()
	{
		$iteratorArray = [];

		if ($this->getName()) {
			
			foreach ($this->data as $propertyName => $value) {
				$concatedName = $this->namingStrategy->concatNamesForExtraction($this->getName(), $name);
				$name = $this->namingStrategy->getNameForExtraction($concatedName);
				$iteratorArray[$name] = $value;
			}

			return $iteratorArray;
		}

		foreach ($this->data as $name => $value) {
			$name = $this->namingStrategy->getNameForExtraction($name);
			$iteratorArray[$name] = $value;
		}

		return $iteratorArray;
	}
}