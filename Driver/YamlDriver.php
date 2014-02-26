<?php

namespace Vivait\SettingsBundle\Driver;

use Symfony\Component\DependencyInjection\ContainerInterface;

class YamlDriver implements ParametersStorageInterface {
	/* @var $container ContainerInterface */
	private $container;

	public function __construct(ContainerInterface $container) {
		$this->container = $container;
	}

	public function has($key) {
		return $this->container->hasParameter($key);
	}

	public function get($key) {
		return $this->container->getParameter($key);
	}

	public function set($key, $value) {
		throw new \BadMethodCallException('Set operation is not supported for YamlDriver');
	}

	public function remove($key) {
		throw new \BadMethodCallException('Remove operation is not supported for YamlDriver');
	}
}
