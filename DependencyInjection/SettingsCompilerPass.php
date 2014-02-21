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

		$definition = $container->getDefinition(
			'vivait_settings.registry'
		);

		$this->processServices($container, $definition);
		$this->processDrivers($container, $definition);
	}

	private function processServices(ContainerBuilder $container, Definition $definition) {
		$taggedServices = $container->findTaggedServiceIds(
			'vivait_settings.register'
		);

		foreach ($taggedServices as $id => $tagAttributes) {
			foreach ($tagAttributes as $attributes) {
				if (!empty($attributes["alias"])) {
					$definition->addMethodCall(
						'addDefinition',
						array(new Reference($id), $attributes["alias"])
					);
				}
			}
		}
	}

	private function processDrivers(ContainerBuilder $container, Definition $definition) {
		$taggedServices = $container->findTaggedServiceIds(
			'vivait_settings.register.driver'
		);
		foreach ($taggedServices as $id => $tagAttributes) {
			foreach ($tagAttributes as $attributes) {
				if (!empty($attributes["driver"])) {
					$definition->addMethodCall(
						'addDriver',
						array(new Reference($id), new Reference($attributes["driver"]))
					);
				}
			}
		}
	}
}
