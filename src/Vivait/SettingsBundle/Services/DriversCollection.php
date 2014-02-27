<?php
namespace Vivait\SettingsBundle\Services;

use Monolog\Logger;

class DriversCollection extends \SplObjectStorage {
	/* @var $logger Logger */
	protected $logger;

	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}

	public function get($key) {
		$stack = array();

		// Try each driver
		foreach ($this as $driver) {
			if ($driver->has($key)) {
				return $driver->get($key);
			}

			$stack[] = get_class($driver);
		}

		// Log the failure
		$this->logger->error(sprintf('No setting "%s" found, tried drivers "%s".', $key, implode(', ', $stack)));

		return null;
	}

} 