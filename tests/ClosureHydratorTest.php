<?php

use ieu\Hydrator\ClosureHydrator;
use ieu\Hydrator\Group;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;
use ieu\Hydrator\Types\ArrayType;
use ieu\Hydrator\Types\IntegerType;
use ieu\Hydrator\Types\ClosureType;
use ieu\Hydrator\Collections\PropertyCollection;
use ieu\Hydrator\Collections\ColumnCollection;

include __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'Entity.php';
include __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'EntityType.php';

/**
 * @author  Philipp Steingrebe <philipp@steingrebe.de>
 */
class ClosureHydratorTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->namingStrategy = new UnderscoreNamingStrategy();
		$this->hydrator = (new ClosureHydrator($this->namingStrategy))
			->setType('tags', ArrayType::instance());
	}

	public function testUnderscoreNamingStrategy()
	{
		$this->assertEquals('anCamelCase', $this->namingStrategy->getNameForHydration('an_camel_case'));
		$this->assertEquals('an_underscore_slug', $this->namingStrategy->getNameForExtraction('anUnderscoreSlug'));
	}

	public function testHydration()
	{
		$e = new Entity;
		$this->hydrator->hydrate($e, ['id' => 1, 'name' => 'Entity', 'tags' => '["A", "B", "C"]']);

		$this->assertEquals(1, $e->getId());
		$this->assertEquals('Entity', $e->getName());
	}

	public function testExtraction()
	{
		$e = new Entity;
		$this->hydrator->hydrate($e, ['id' => 1, 'name' => 'Entity', 'tags' => '["A", "B", "C"]']);

		$this->assertEquals(1, $e->getId());
		$this->assertEquals('Entity', $e->getName());
	}

	public function testTypes()
	{
		$e = new EntityType;

		$hydrator = (new ClosureHydrator($this->namingStrategy))
			->setType('type', ArrayType::instance());

		$hydrator->hydrate($e, ['type' => '["A", "B", "C"]']);

		$this->assertEquals(["A", "B", "C"] , $e->getType());
		$this->assertEquals('["A","B","C"]' , $hydrator->extract($e)['type']);

		$hydrator
			->setType('type', IntegerType::instance())
			->hydrate($e, ['type' => '1']);

		$this->assertEquals(1 , $e->getType());
		$this->assertEquals(1 , $hydrator->extract($e)['type']);
	}

	public function testUnpackingOnHydration()
	{
		$hydrator = (new ClosureHydrator($this->namingStrategy))
			->setType('type', new ClosureType(function($value){
				return new PropertyCollection(['prop1' => 1, 'prop2' => 2]);
			}, function($value){}));

		$object = $hydrator->hydrate(new stdClass, ['type' => null]);

		$this->assertTrue($object->prop1 === 1);
		$this->assertTrue($object->prop2 === 2);
	}

	public function testUnpackingOnExtraction()
	{
		$hydrator = (new ClosureHydrator($this->namingStrategy))
			->setType('type', new ClosureType(function($value){},
			function($value){
				return new ColumnCollection(['prop1' => 1, 'prop2' => 2]);
			}));

		$data = $hydrator->extract ((object)['type' => null]);

		$this->assertTrue($data['prop1'] === 1);
		$this->assertTrue($data['prop2'] === 2);
	}

	public function testUnpackingOnExtractionWithNamePrefix()
	{
		$hydrator = (new ClosureHydrator($this->namingStrategy))
			->setType('typeCase', new ClosureType(function($value){},
			function($value){
				return new ColumnCollection(['.prop1Case' => 1, '.prop2Case' => 2]);
			}));

		$data = $hydrator->extract ((object)['typeCase' => null]);

		$this->assertTrue($data['type_case_prop1_case'] === 1);
		$this->assertTrue($data['type_case_prop2_case'] === 2);
	}
}