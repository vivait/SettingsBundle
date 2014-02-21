<?php
namespace Vivait\SettingsBundle\Driver;

interface ParametersStorageInterface {

	public function has($key);

	public function get($key);

	public function set($key, $value);

	public function remove($key);
}