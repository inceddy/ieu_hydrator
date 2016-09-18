<?php

namespace ieu\Hydrator\Collections;
class ColumnCollection extends AbstractCollection {

	protected function getIteratorArray()
	{
		$iteratorArray = [];

		if ($this->getName()) {
			
			foreach ($this->data as $propertyName => $value) {
				if ($propertyName[0] === '.') {
					$propertyName = $this->namingStrategy->concatPropertyNames($this->getName(), substr($propertyName, 1));
				}

				$name = $this->namingStrategy->getNameForExtraction($propertyName);
				$iteratorArray[$name] = $value;
			}

			return $iteratorArray;
		}

		foreach ($this->data as $name => $value) {
			if ($name[0] === '.') {
				trigger_error('You can\'t use the dot prefix without setting a name');
				$name = substr($name, 1);
			}

			$name = $this->namingStrategy->getNameForExtraction($name);

			$iteratorArray[$name] = $value;
		}

		return $iteratorArray;
	}
};