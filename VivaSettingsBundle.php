<?php

namespace Viva\SettingsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Viva\SettingsBundle\DependencyInjection\SettingsCompilerPass;

class VivaSettingsBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new SettingsCompilerPass());
	}
}
