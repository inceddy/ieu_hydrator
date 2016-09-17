<?php

use ieu\Hydrator\ClosureHydrator;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;
use ieu\Hydrator\Types\ArrayType;
use ieu\Hydrator\Types\IntegerType;
use ieu\Hydrator\Types\ClosureType;
use ieu\Hydrator\Collections\ColumnCollection;
use ieu\Hydrator\Collections\PropertyCollection;
use ieu\Hydrator\Group;

//include __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'Entity.php';
//include __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'EntityType.php';

/**
 * @author  Philipp Steingrebe <philipp@steingrebe.de>
 */
class GroupTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->hydrator = (new ClosureHydrator)
			->setGroup('createdAt', ['.timeStamp', '.timeZone'], Group::HYDRATION)
			->setGroup('priceCombined', ['valueAmount', 'currency'], Group::EXTRACTION);
	}

	public function testHydrationWithGroup()
	{
		$data = ['created_at_time_stamp' => 123456, 'created_at_time_zone' => 'US/Pacific'];
		$object = $this->hydrator->hydrate(new stdClass, $data);

		$this->assertInstanceOf(ColumnCollection::CLASS, $object->createdAt);

		$this->assertEquals(123456,       $object->createdAt->timeStamp);
		$this->assertEquals('US/Pacific', $object->createdAt->timeZone);
	}

	public function testExtractionWithGroup()
	{
		$object = (object)['valueAmount' => 200, 'currency' => 'EUR'];
		$data = $this->hydrator->extract($object);

		$this->assertInstanceOf(PropertyCollection::CLASS, $data['price_combined']);

		$this->assertEquals(200,   $data['price_combined']->valueAmount);
		$this->assertEquals('EUR', $data['price_combined']->currency);
	}
}