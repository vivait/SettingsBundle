<?php
namespace Vivait\SettingsBundle\Services;

use Monolog\Logger;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

class SettingsChain {
	/* @var $definitions \SplObjectStorage */
	protected $definitions;

	/* @var $logger Logger  */
	protected $logger;

	/* @var $accessor PropertyAccess */
	protected $accessor;

	/* @var $driver ParametersStorageInterface */
	protected $default_driver;

	public function __construct(Logger $logger, ParametersStorageInterface $default_driver = null) {
		$this->logger         = $logger;
		$this->default_driver = $default_driver;
		$this->definitions    = new \SplObjectStorage();
		$this->accessor       = PropertyAccess::createPropertyAccessor();
	}

	/**
	 * Sets default_driver
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $default_driver
	 */
	public function setDefaultDriver($default_driver) {
		$this->default_driver = $default_driver;
		return $this;
	}

	/**
	 * @return \Vivait\SettingsBundle\Driver\ParametersStorageInterface
	 */
	public function getDefaultDriver() {
		return $this->default_driver;
	}

	public function addDefinition($definition, $alias)
	{
		$this->definitions->attach($definition, array(
			'alias' => $alias,
			'drivers' => array()
		));

		return $this;
	}

	public function addDriver($object, $driver) {
		if (!($definition = $this->getDefinition($object))) {
			$definition = array(
				'drivers' => array()
			);
		}

		// Add the driver to the list of drivers
		$definition['drivers'][] = $driver;

		$this->definitions->attach($object, $definition);

		return $this;
	}

	/**
	 * Returns a defined service
	 * @param $object object
	 * @return null|string
	 */
	public function getDefinition($object)
	{
		if ($this->definitions->contains($object)) {
			return $this->definitions->offsetGet($object);
		}

		return null;
	}

	/**
	 * Returns the defined services
	 * @return \SplObjectStorage
	 */
	public function getDefinitions()
	{
		return $this->definitions;
	}

	public function configureAll() {
		foreach ($this->getDefinitions() as $definition) {
			$this->configure($definition);
		}

		return $this;
	}

	public function configure($object) {
		$definition = $this->getDefinition($object);
		$alias      = '['. $definition['alias'] .']';
		$drivers    = $definition['drivers'];

		// Add the default driver as a fallback
		if ($this->default_driver && !in_array($this->default_driver, $drivers)) {
			$drivers[] = $this->default_driver;
		}

		foreach ($drivers as $driver) {
			if ($driver->has($alias)) {
				$settings = $driver->get($alias);

				foreach ($settings as $key => $value) {
					try {
						$this->accessor->setValue($object, $key, $value);
					}
					catch (AccessException $e) {
						$this->logger->error(sprintf('Could not set setting %s[%s] for object type %s', $definition['alias'], $key, get_class($object)));
					}
				}

				return $object;
			}
		}

		$this->logger->error(sprintf('No setting group "%s" found.', $definition['alias']));

		return $object;
	}
}