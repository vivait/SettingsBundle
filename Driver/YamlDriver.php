<?php
/**
 * Created by PhpStorm.
 * User: lewis
 * Date: 19/02/2014
 * Time: 11:45AM
 */

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
		//$this->container->setParameter($key, $value);
	}

	public function remove($key) {
		throw new \BadMethodCallException('Remove operation is not supported for YamlDriver');
		//$this->container->setParameter($key, null);
	}
}