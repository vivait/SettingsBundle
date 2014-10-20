<?php

namespace Vivait\SettingsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class SettingsCompilerPass implements CompilerPassInterface {
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('vivait_settings.registry')) {
			return;
		}

		$this->processDrivers($container, $container->getDefinition('vivait_settings.registry.drivers'));
		$this->processForms($container, $container->getDefinition('vivait_settings.registry.forms'));
	}

	private function processDrivers(ContainerBuilder $container, Definition $definition) {
		$services = $container->getParameter('vivait_settings.drivers.default');

		foreach ($services as $alias => $service) {
			$definition->addMethodCall(
				'addDriver',
				array($alias, new Reference($service))
			);
		}
	}

	private function processForms(ContainerBuilder $container, Definition $definition) {
		$taggedServices = $container->findTaggedServiceIds(
			'vivait_settings.register.form'
		);
		foreach ($taggedServices as $id => $tagAttributes) {
			foreach ($tagAttributes as $attributes) {
				$definition->addMethodCall(
					'attach',
					array(new Reference($id), array(
						'title' => $attributes['title'],
						'for'   => $attributes['for']
					))
				);
			}
		}
	}
}
