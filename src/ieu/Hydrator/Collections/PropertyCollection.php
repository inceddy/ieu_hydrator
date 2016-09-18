<?php

namespace ieu\Hydrator\Collections;
class PropertyCollection extends AbstractCollection {
	protected function getIteratorArray()
	{
		$iteratorArray = [];

		if ($this->getName()) { // Name is something like `prefixName`
			
			foreach ($this->data as $propertyName => $value) {
				if ($propertyName[0] === 0) {
					$name = $this->namingStrategy->concatNamesForHydration($this->getName(), substr($propertyName, 1));
					//$name = $this->namingStrategy->getNameForExtraction($concatedName);
				}
				else {
					$name = substr($propertyName, 1);
				}

				$iteratorArray[$name] = $value;
			}

			return $iteratorArray;
		}

		foreach ($this->data as $name => $value) {
			if ($name[0] === '.') {
				trigger_error('You can\'t use the dot prefix without setting a name');
				$name = substr($name, 1);
			}
			$iteratorArray[$name] = $value;
		}

		return $iteratorArray;
	}
}