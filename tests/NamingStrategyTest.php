<?php

use ieu\Hydrator\ClosureHydrator;
use ieu\Hydrator\NamingStrategies\UnderscoreNamingStrategy;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'Entity.php';

/**
 * @author  Philipp Steingrebe <philipp@steingrebe.de>
 */
class NamingStrategyTest extends \PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->namingStrategy = new UnderscoreNamingStrategy();
		$this->hydrator = new ClosureHydrator($this->namingStrategy);
	}

	public function testUnderscoreNamingStrategy()
	{
		$object = new Entity;
		$data = $this->hydrator->extract($object);
		$this->assertEquals(['id', 'name', 'tags', 'camel_case'], array_keys($data));

	}
}