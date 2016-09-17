<?php

/**
 * @author  Philipp Steingrebe <philipp@steingrebe.de>
 */

class CollectionTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->namingStrategy = new ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;
	}

	public function testIteratorAggregate()
	{
		$collection = new ieu\Hydrator\Collections\ColumnCollection;
		$this->assertInstanceOf(\Traversable::CLASS, $collection->getIterator());
	}

	public function testArrayAccess()
	{
		$collection = new ieu\Hydrator\Collections\ColumnCollection(['key' => 'value'], $this->namingStrategy);
		$this->assertEquals('value', $collection['key']);

		$collection['key'] = 'new value';
		$this->assertEquals('new value', $collection['key']);
	}

	public function testObjectAccess()
	{
		$collection = new ieu\Hydrator\Collections\ColumnCollection(['key' => 'value'], $this->namingStrategy);
		$this->assertEquals('value', $collection->key);
		
		$collection->key = 'new value';
		$this->assertEquals('new value', $collection->key);
	}

	public function testColumnCollection()
	{
		$collection = new ieu\Hydrator\Collections\ColumnCollection(['timeZone' => 'Western\Berlin'], $this->namingStrategy);
		$iteratorArray = $collection->getIterator()->getArrayCopy();
		$this->assertArrayHasKey('timeZone', $iteratorArray);
	}

	public function testPropertyCollection()
	{
		$collection = new ieu\Hydrator\Collections\PropertyCollection(['timeZone' => 'Western\Berlin'], $this->namingStrategy);
		$iteratorArray = $collection->getIterator()->getArrayCopy();
		$this->assertArrayHasKey('time_zone', $iteratorArray);
	}
}