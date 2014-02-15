<?php

namespace Viva\SettingsBundle\Tests\Controller;

use Viva\SettingsBundle\Entity\Settings;
use Viva\SettingsBundle\Services\SettingsService;

class SettingsServiceTest extends \PHPUnit_Framework_TestCase
{
	protected $doctrine;

	public function setUp() {
		// Mock doctrine
		$this->doctrine = $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')
			->disableOriginalConstructor()
			->getMock();
	}

    public function testAddDefinition() {
		$definition1 = $this->getMockForAbstractClass('Viva\SettingsBundle\Interfaces\Settings');
		$definition2 = $this->getMockForAbstractClass('Viva\SettingsBundle\Interfaces\Settings');
		$definition3 = $this->getMockForAbstractClass('Viva\SettingsBundle\Interfaces\Settings');

		$service = new SettingsService($this->doctrine);

		// Check for chainability
		$this->assertInstanceOf('Viva\SettingsBundle\Services\SettingsService', $service->addDefinition($definition1, 'alias1'));
		// Check it has added it ok
		$this->assertAttributeCount(1, 'definitions', $service);

		// Check we can add more than 1
		$this->assertAttributeCount(2, 'definitions', $service->addDefinition($definition2, 'alias2'));

		// Add a duplicate alias but different class
		$this->assertAttributeCount(2, 'definitions', $service->addDefinition($definition3, 'alias1'));

		return $service;
    }

	/**
	 * @depends testAddDefinition
	 */
	public function testGetDefinition(SettingsService $service) {
		// Check it can fetch it
		$this->assertInstanceOf('Viva\SettingsBundle\Interfaces\Settings', $service->getDefinition('alias1'));

		// Check it doesn't error when fetching non-existent classes
		$this->assertNull($service->getDefinition('noneexistent'));
	}

	public function testGetDefinitions() {
		$service = new SettingsService($this->doctrine);

		// Check it can handle no items
		$this->assertInternalType('array', $service->getDefinitions());

		// Add some items
		$service->addDefinition($this->getMockForAbstractClass('Viva\SettingsBundle\Interfaces\Settings'), 'alias1');
		$service->addDefinition($this->getMockForAbstractClass('Viva\SettingsBundle\Interfaces\Settings'), 'alias2');
		$service->addDefinition($this->getMockForAbstractClass('Viva\SettingsBundle\Interfaces\Settings'), 'alias3');

		// Check it's returning all 3 as an array
		$this->assertInternalType('array', $service->getDefinitions());
		$this->assertCount(3, $service->getDefinitions());
	}

	public function testGetSettings() {
		// Mock the settings entities
		$entity = $this->getMock('Viva\SettingsBundle\Entity\Settings', array('getServiceAlias', 'getAlias', 'getValue'));

		$entity
			->expects($this->exactly(3))
			->method('getServiceAlias')
			->will($this->onConsecutiveCalls('testservice1', 'testservice1', 'testservice2'));

		$entity
			->expects($this->exactly(3))
			->method('getAlias')
			->will($this->onConsecutiveCalls('testsetting1', 'testsetting2', 'testsetting3'));

		$entity
			->expects($this->exactly(3))
			->method('getValue')
			->will($this->onConsecutiveCalls('testvalue1', 'testvalue2', 'testvalue3'));

		// Mock the repository
		$repository = $this->getMock('EntityRepository', array('findAll'));
		$repository
			->expects($this->any())
			->method('findAll')
			->will($this->returnValue(array(
				$entity,
				clone $entity,
				clone $entity,
			)));

		// Mock doctrine
		$this->doctrine
			->expects($this->any())
			->method('getRepository')
			->will($this->returnValue($repository));

		$service = new SettingsService($this->doctrine);

		// Test they have been grouped properly
		$this->assertInternalType('array', $service->getSettings());
		$this->assertCount(2, $service->getSettings());

		// Test each group
		$settings = $service->getSettings();
		$first = reset($settings);
		$second = next($settings);

		// First
		$this->assertInternalType('array', $first);
		$this->assertCount(2, $first);

		// Second
		$this->assertInternalType('array', $second);
		$this->assertCount(1, $second);

		return $service;
	}

	/**
	 * @depends testGet
	 */
	public function testGet(SettingsService $service) {
		// Test for non-existent settings group alias
		$this->assertNull($service->get('noneexistent', 'noneexistentservice'));

		// Test for non-existent settings alias
		$this->assertNull($service->get('noneexistent', 'testservice1'));

		// Test for cross-group pollination
		$this->assertNull($service->get('testsetting2', 'testservice2'));

		// Test group 1
		$this->assertEquals('testvalue2', $service->get('testsetting2', 'testservice1'));
		// Test group 2
		$this->assertEquals('testvalue3', $service->get('testsetting3', 'testservice2'));
	}
}