<?php

namespace Vivait\SettingsBundle\Tests\Controller;

use Vivait\SettingsBundle\Services\DriversCollection;

class DriverCollectionTest extends \PHPUnit_Framework_TestCase
{
	protected $driver_collection;
	protected $doctrine_driver;

	public function setUp() {
		$this->doctrine_driver = $this->getMockBuilder('Vivait\SettingsBundle\Driver\DoctrineDriver')
			->disableOriginalConstructor()
			->getMock();

		$logger = $this->getMockBuilder('Monolog\Logger')
			->disableOriginalConstructor()
			->getMock();

		$this->driver_collection = new DriversCollection($logger);
		$this->driver_collection->attach($this->doctrine_driver);
	}

	/**
	 * @test
	 */
	public function hasReturnsTrueWhenSettingExisits() {

		$this->doctrine_driver->expects($this->any())
			->method('has')
			->with('existing_parameter')
			->will($this->returnValue(true));

		$this->assertTrue($this->driver_collection->has('existing_parameter'));
	}

	/**
	 * @test
	 */
	public function hasReturnsFalseWhenSettingDoesntExisit() {
		$this->doctrine_driver->expects($this->any())
			->method('has')
			->with('non_existing_parameter')
			->will($this->returnValue(false));

		$this->assertFalse($this->driver_collection->has('non_existing_parameter'));
	}
}