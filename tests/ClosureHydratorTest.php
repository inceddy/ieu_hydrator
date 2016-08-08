<?php

use ieu\Hydrator\ClosureHydrator;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;
use ieu\Hydrator\Types\ArrayType;
use ieu\Hydrator\Types\IntegerType;

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
			->setProperty('id')
			->setProperty('name')
			->setProperty('tags', ArrayType::instance());
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
			->setProperty('type', ArrayType::instance());

		$hydrator->hydrate($e, ['type' => '["A", "B", "C"]']);

		$this->assertEquals(["A", "B", "C"] , $e->getType());
		$this->assertEquals('["A","B","C"]' , $hydrator->extract($e)['type']);

		$hydrator
			->setProperty('type', IntegerType::instance())
			->hydrate($e, ['type' => '1']);

		$this->assertEquals(1 , $e->getType());
		$this->assertEquals(1 , $hydrator->extract($e)['type']);
	}
}