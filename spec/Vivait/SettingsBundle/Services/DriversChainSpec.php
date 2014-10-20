<?php

namespace spec\Vivait\SettingsBundle\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

/**
 * @mixin \Vivait\SettingsBundle\Services\DriversChain;
 */
class DriversChainSpec extends ObjectBehavior
{
	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $definition1
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $definition2
	 */
	function it_should_store_and_retrieve_drivers(ParametersStorageInterface $definition1, ParametersStorageInterface $definition2) {
		$this->addDriver('test1', $definition1);
		$this->addDriver('test2', $definition2);

		$this->getDrivers()
			->shouldHaveCount(2);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $definition
	 */
	function it_should_retrieve_a_specific_driver(ParametersStorageInterface $definition, ParametersStorageInterface $definition1) {
		$this->addDriver('test1', $definition);
		$this->addDriver('test2', $definition1);

		$this->getDrivers()
			->shouldHaveCount(2);
	}

    function it_should_throw_an_exception_when_getting_undefined_driver(){
        $this->shouldThrow('Vivait\\SettingsBundle\\Exception\\DriverNotFoundException')->duringGetDriver('invalid');
    }

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $definition
	 */
	function it_should_remove_drivers(ParametersStorageInterface $definition) {
		$this->addDriver('test1', $definition);
		$this->addDriver('test2', $definition);

		$this->removeDriver('test2');

		$this->getDrivers()
			->shouldHaveCount(1);
	}

	function it_should_not_error_when_removing_undefined_drivers() {
		$this->removeDriver('invalid');
	}
}