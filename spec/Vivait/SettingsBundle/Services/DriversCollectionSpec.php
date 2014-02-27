<?php

namespace spec\Vivait\SettingsBundle\Services;

use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

/**
 * @mixin \Vivait\SettingsBundle\Services\DriversCollection;
 */
class DriversCollectionSpec extends ObjectBehavior
{
	/**
	 * @param \Monolog\Logger $logger
	 */
	function let(Logger $logger) {
		$this->beConstructedWith($logger);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver2
	 */
	function it_should_stop_at_the_first_driver(ParametersStorageInterface $driver1, ParametersStorageInterface $driver2) {
		$value = 'value';

		$driver1->has('key1')->willReturn(true)->shouldBeCalled();
		$driver1->get('key1')->willReturn($value)->shouldBeCalled();

		$driver2->has('key1')->shouldNotBeCalled();
		$driver2->get('key1')->shouldNotBeCalled();

		$this->attach($driver1);
		$this->attach($driver2);

		$this->get('key1')
			->shouldBe($value);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver1
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver2
	 */
	function it_should_check_all_drivers(ParametersStorageInterface $driver1, ParametersStorageInterface $driver2) {
		$value = 'value';

		$driver1->has('key2')->willReturn(false)->shouldBeCalled();
		$driver1->get('key2')->shouldNotBeCalled();

		$driver2->has('key2')->willReturn(true)->shouldBeCalled();
		$driver2->get('key2')->willReturn($value)->shouldBeCalled();

		$this->attach($driver1);
		$this->attach($driver2);

		$this->get('key2')
			->shouldBe($value);
	}

	/**
	 * @param \Monolog\Logger $logger
	 */
	function it_should_log_invalid_keys(Logger $logger) {
		$key = 'invalid';

		$logger->error(Argument::containingString($key))->willReturn(true)->shouldBeCalled();

		$this->get('invalid');
	}
}