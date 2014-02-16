<?php

namespace Vivait\SettingsBundle\Interfaces;

use Symfony\Component\Form\AbstractType;
use Vivait\SettingsBundle\Services\SettingsService;

interface Settings
{
	/**
	 * @return string The settings panel name
	 */
	public function getSettingsName();

	/**
	 * @return AbstractType
	 */
	public function getSettingsForm();

	public function setSettingsRegistry(SettingsService $service);

	/**
	 * @return SettingsService
	 */
	public function getSettingsRegistry();
}
