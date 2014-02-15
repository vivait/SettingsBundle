<?php

namespace Viva\SettingsBundle\Interfaces;

use Symfony\Component\Form\AbstractType;
use Viva\SettingsBundle\Services\SettingsService;

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
