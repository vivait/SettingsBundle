<?php

namespace Vivait\SettingsBundle\EventListener;

use Vivait\BootstrapBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener {
	/**
	 * @param ConfigureMenuEvent $event
	 */
	public function onMenuConfigure(ConfigureMenuEvent $event) {
		$menu = $event->getMenu();

		$maintenance = $menu->getChild('Maintenance') ?: $menu->addChild('Maintenance', array(
			'dropdown' => true,
			'caret'    => true,
		));

		$maintenance->addChild('Settings', array(
			'icon'  => 'wrench',
			'route' => 'vivait_settings_maintenance_settings'
		));
	}
}