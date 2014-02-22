<?php
namespace Vivait\SettingsBundle\Services;

use Monolog\Logger;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

class FormChain {
	/* @var $forms \SplObjectStorage */
	protected $forms;

	public function __construct() {
		$this->definitions = new \SplObjectStorage();
	}

	public function addDefinition($definition, $for, $title = '')
	{
		var_dump('adding');
		$this->definitions->attach($definition, array(
			'for'   => $for,
			'title' => $title
		));

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
}