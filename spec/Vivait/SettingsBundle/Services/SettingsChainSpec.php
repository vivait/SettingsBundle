<?php

namespace spec\Vivait\SettingsBundle\Services;

use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;
use Vivait\SettingsBundle\Services\DriversChain;

class SettingsChainSpec extends ObjectBehavior {

	/**
	 * @param \Monolog\Logger $logger
	 * @param \Vivait\SettingsBundle\Services\DriversChain $drivers
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver2
	 */
	function let(Logger $logger, DriversChain $drivers, ParametersStorageInterface $driver1, ParametersStorageInterface $driver2) {
		$drivers->getDriver('driver1')
			->willReturn($driver1);

		$drivers->getDriver('driver2')
			->willReturn($driver2);

		$this->beConstructedWith($drivers, $logger);
	}

	function it_should_create_a_driver_collection_based_on_arguments() {
		$drivers = $this->drivers('driver1', 'driver2');

		$drivers->shouldHaveType('\Vivait\SettingsBundle\Services\DriversColection');
	}
}
