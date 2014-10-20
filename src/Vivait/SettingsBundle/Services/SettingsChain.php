<?php
namespace Vivait\SettingsBundle\Services;

use Monolog\Logger;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

class SettingsChain {
	/* @var $logger Logger */
	protected $logger;

	/* @var $drivers DriversChain */
	protected $driversChain;

	public function __construct(DriversChain $drivers, Logger $logger) {
		$this->driversChain = $drivers;
		$this->logger       = $logger;
	}

    /**
     * @param array $drivers
     * @return DriversCollection
     */
	public function drivers(array $drivers = null) {
		$drivers = $drivers ?: $this->driversChain->getDrivers();

		$collection = new DriversCollection($this->logger);

		foreach ($drivers as $driver) {
			// Have they passed along an alias?
			if (!($driver instanceOf ParametersStorageInterface)) {
				$driver = $this->driversChain->getDriver($driver);
			}

			$collection->attach($driver);
		}

		return $collection;
	}

	public function get($key) {
		return $this->drivers()->get($key);
	}
}