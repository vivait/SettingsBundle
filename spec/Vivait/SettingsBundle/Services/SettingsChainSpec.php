<?php

namespace spec\Vivait\SettingsBundle\Services;

use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

class SettingsChainSpec extends ObjectBehavior {
	/**
	 * @param \Monolog\Logger $logger
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 */
	function let(Logger $logger, ParametersStorageInterface $driver) {

		$this->beConstructedWith($logger, $driver);
	}

	function it_is_initializable() {
		$this->shouldHaveType('Vivait\SettingsBundle\Services\SettingsChain');
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 */
	function it_should_accept_a_definition_with_a_single_driver(ParametersStorageInterface $driver) {
		$this->addDefinition(($obj = new \stdClass()), 'test1')
			->shouldReturn($this);

		$this->addDriver($obj, $driver)
			->shouldReturn($this);
	}

	/**
	 * @param \Monolog\Logger $logger
	 */
	function it_should_log_no_settings_group_found($logger) {
		$logger->error(Argument::containingString('test1'))
			->shouldBeCalled();

		$this->addDefinition(new \stdClass(), 'test1')
			->shouldReturn($this);

		$this->configureAll()
			->shouldReturn($this);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 */
	function it_should_fallback_to_the_main_driver(ParametersStorageInterface $driver) {
		$driver->has('[test1]')
			->willReturn(false)
			->shouldBeCalled();

		$this->addDefinition(new \stdClass(), 'test1')
			->shouldReturn($this);

		$this->configureAll()
			->shouldReturn($this);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver2
	 */
	function it_should_stop_at_the_first_driver_if_the_settings_exist(ParametersStorageInterface $driver1, ParametersStorageInterface $driver2) {
		$driver1->has('[test1]')
			->willReturn(true)
			->shouldBeCalled();

		$driver1->get('[test1]')
			->willReturn(array())
			->shouldBeCalled();

		$driver2
			->has('[test1]')
			->shouldNotBeCalled();

		$this->addDefinition(($obj = new \stdClass()), 'test1')
			->shouldReturn($this);

		$this->addDriver($obj, $driver1);
		$this->addDriver($obj, $driver2);

		$this->configureAll()
			->shouldReturn($this);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver2
	 */
	function it_should_fallback_to_the_second_driver_in_an_array(ParametersStorageInterface $driver1, ParametersStorageInterface $driver2) {
		$driver1->has('[test1]')
			->willReturn(false)
			->shouldBeCalled();

		$driver2->has('[test1]')
			->willReturn(true)
			->shouldBeCalled();

		$driver2->get('[test1]')
			->willReturn(array())
			->shouldBeCalled();

		$this->addDefinition(($obj = new \stdClass()), 'test1')
			->shouldReturn($this);

		$this->addDriver($obj, $driver1);
		$this->addDriver($obj, $driver2);

		$this->configureAll()
			->shouldReturn($this);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 */
	function it_should_let_me_get_all_definitions(ParametersStorageInterface $driver1) {
		$this->addDefinition(($obj = new \stdClass()), 'test1');
		$this->addDefinition(($obj = new \stdClass()), 'test2');

		$this->getDefinitions($obj)->shouldHaveCount(2);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 */
	function it_should_let_me_get_a_particular_definition(ParametersStorageInterface $driver1) {
		$this->addDefinition(($obj = new \stdClass()), 'test1')
			->shouldReturn($this);

		$this->addDriver($obj, $driver1);

		$this->getDefinition($obj)->shouldReturn(Array(
			'alias'   => 'test1',
			'drivers' => array($driver1)
		));
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 */
	function it_should_return_null_for_non_existent_definitions(ParametersStorageInterface $driver1) {
		$this->addDefinition(new \stdClass(), 'test1');

		$this->getDefinition(new \stdClass())
			->shouldReturn(null);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 */
	function it_should_let_me_configure_a_single_definition(ParametersStorageInterface $driver1) {
		$driver1->has('[test1]')
			->willReturn(true)
			->shouldBeCalled();

		$driver1->get('[test1]')
			->willReturn(array())
			->shouldBeCalled();

		$this->addDefinition(($obj = new \stdClass()), 'test1')
			->shouldReturn($this);

		$this->addDriver($obj, $driver1);

		$this->configure($obj)
			->shouldReturn($obj);
	}
}
