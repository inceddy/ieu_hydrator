<?php

use ieu\Hydrator\ClosureHydrator;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;
use ieu\Hydrator\Types\ArrayType;
use ieu\Hydrator\Types\IntegerType;
use ieu\Hydrator\Types\DateTimeType;
use ieu\Hydrator\Types\ClosureType;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'EntityType.php';

/**
 * @author  Philipp Steingrebe <philipp@steingrebe.de>
 */
class TypeTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->namingStrategy = new UnderscoreNamingStrategy();
		$this->hydrator = new ClosureHydrator($this->namingStrategy);
	}

	public function testDateTimeType()
	{
		$object = new EntityType;
		$time = time();

		$this->hydrator->setType('type', DateTimeType::instance());

		// Hydration
		$this->hydrator->hydrate($object, ['type' => $time]);
		$this->assertEquals($time, $object->getType()->getTimestamp());

		// Extraction
		$data = $this->hydrator->extract($object);
		$this->assertEquals($time, $data['type']);

		// Null/Empty values
		$this->hydrator->hydrate($object, ['type' => null]);
		$this->assertEquals(null, $object->getType());

		$this->hydrator->hydrate($object, ['type' => '']);
		$this->assertEquals(null, $object->getType());

		$object->setType(null);
		$data = $this->hydrator->extract($object);
		$this->assertEquals(null, $data['type']);

		unset($object);
	}

	public function testArrayType()
	{
		$object = new EntityType;
		$array = [1, 2, 3];
		$arrayEncoded = json_encode($array);

		$this->hydrator->setType('type', ArrayType::instance());

		// Hydration
		$this->hydrator->hydrate($object, ['type' => $arrayEncoded]);
		$this->assertEquals([1, 2, 3], $object->getType());

		// Extraction
		$data = $this->hydrator->extract($object);
		$this->assertEquals($arrayEncoded, $data['type']);

		unset($object);
	}

	public function testClosureType()
	{
		$object = new EntityType;
		$dataOrg = ['type' => 1];

		$this->hydrator->setType('type', new ClosureType(function($value){
			return $value * 2;
		}, function($value){
			return $value / 2;
		}));

		// Hydration
		$this->hydrator->hydrate($object, $dataOrg);
		$this->assertEquals(2, $object->getType());

		// Extraction
		$data = $this->hydrator->extract($object);
		$this->assertEquals($dataOrg, $data);

		unset($object);
	}
}