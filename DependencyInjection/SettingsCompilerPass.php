<?php

namespace Viva\SettingsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SettingsCompilerPass implements CompilerPassInterface {
	public function process(ContainerBuilder $container)
	{
		if (!$container->hasDefinition('viva_settings.registry')) {
			return;
		}

		$definition = $container->getDefinition(
			'viva_settings.registry'
		);

		$taggedServices = $container->findTaggedServiceIds(
			'viva_settings.register'
		);
		foreach ($taggedServices as $id => $tagAttributes) {
			foreach ($tagAttributes as $attributes) {
				$definition->addMethodCall(
					'addDefinition',
					array(new Reference($id), $attributes["alias"])
				);
			}
		}
	}
}
