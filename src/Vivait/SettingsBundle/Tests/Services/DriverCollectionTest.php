<?php

namespace Vivait\SettingsBundle\Tests\Controller;

use Vivait\SettingsBundle\Driver\DoctrineDriver;
use Vivait\SettingsBundle\Services\DriversCollection;
use Vivait\SettingsBundle\Services\SettingsService;

class DriverCollectionTest extends \PHPUnit_Framework_TestCase
{
	protected $driver_collection;

	public function setUp() {
		$em = $this->getMockBuilder('Doctrine\ORM\EntityManager')
			->disableOriginalConstructor()
			->getMock();
		$repo = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
			->disableOriginalConstructor()
			->getMock();

		$doctrine_driver = $this->getMockBuilder('Vivait\SettingsBundle\Driver')
			->disableOriginalConstructor()
			->getMock();

		$doctrine_driver->expects($this->any())
			->method('has')
			->with('existing_parameter')
			->willReturn('true');
		$doctrine_driver->expects($this->any())
			->method('has')
			->with('non_existing_parameter')
			->willReturn('false');

		$logger = $this->getMockBuilder('Monolog\Logger')
			->disableOriginalConstructor()
			->getMock();

		$this->driver_collection = new DriversCollection($logger);
		$this->driver_collection->attach($doctrine_driver);
	}

	/**
	 * @test
	 */
	public function hasReturnsTrueWhenSettingExisits() {
		$this->assertTrue($this->driver_collection->has('existing_parameter'));
	}

	/**
	 * @test
	 */
	public function hasReturnsFalseWhenSettingDoesntExisit() {
		$this->assertFalse($this->driver_collection->has('non_existing_parameter'));
	}
}