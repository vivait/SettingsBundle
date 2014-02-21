<?php
namespace Vivait\SettingsBundle\Driver;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DoctrineDriver implements ParametersStorageInterface {
	/* @var $entity_manager Registry */
	private $repository;

	/* @var $accessor PropertyAccess */
	protected $accessor;

	protected $settings;

	public function __construct(EntityRepository $em) {
		$this->repository = $em;
		$this->accessor   = PropertyAccess::createPropertyAccessorBuilder()
			->enableExceptionOnInvalidIndex()
			->getPropertyAccessor();

		$this->loadSettings();
	}

	public function has($key) {
		try {
			$this->accessor->getValue($this->settings, $key);
			return true;
		}
		catch (AccessException $e) {
			return false;
		}
	}

	public function get($key) {
		return $this->accessor->getValue($this->settings, $key);
	}

	public function set($key, $value) {
		throw new \BadMethodCallException('Set operation is not supported for YamlDriver');
		//$this->container->setParameter($key, $value);
	}

	public function remove($key) {
		throw new \BadMethodCallException('Remove operation is not supported for YamlDriver');
		//$this->container->setParameter($key, null);
	}

	private function loadSettings() {
		// We've already loaded the settings
		if ($this->settings !== null) {
			return $this->settings;
		}

		// Get all of the settings
		$settings = $this->repository->findAll();

		$entity = array();

		/* @var $setting \Vivait\SettingsBundle\Entity\Settings */
		foreach ($settings as $setting) {
			$entity
			[$setting->getServiceAlias()]
			[$setting->getAlias()] = $setting->getValue();
		}

		return $this->settings = $entity;
	}
}