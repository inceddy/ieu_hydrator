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
	}

	public function testUnderscoreNamingStrategy()
	{
		// Name trnasformation
		$this->assertEquals('camel_case', $this->namingStrategy->getNameForExtraction('camelCase'));
		$this->assertEquals('camelCase', $this->namingStrategy->getNameForHydration('camel_case'));

		// Name concatination
		$this->assertEquals('camelCaseSubNameSubName', $this->namingStrategy->concatPropertyNames('camelCase', 'subName', 'subName'));
		$this->assertEquals('camel_case_sub_name_sub_name', $this->namingStrategy->concatColumnNames('camel_case', 'sub_name', 'sub_name'));
	}

}