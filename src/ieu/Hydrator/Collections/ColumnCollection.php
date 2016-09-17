<?php

namespace ieu\Hydrator\Collections;
class ColumnCollection extends AbstractCollection {

	// No translation needed because collection data is always in PHP case
	protected function getIteratorArray()
	{
		return $this->data;
	}
};