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
		$taggedServices = $container->findTaggedServiceIds(
			'vivait_settings.register.driver'
		);
		foreach ($taggedServices as $id => $tagAttributes) {
			$alias = $id;

			// Have they tagged it with an alias?
			foreach ($tagAttributes as $attributes) {
				if (!empty($attributes["alias"])) {
					$alias = $attributes["alias"];
					break;
				}
			}

			$definition->addMethodCall(
				'addDriver',
				array($alias, new Reference($id))
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
