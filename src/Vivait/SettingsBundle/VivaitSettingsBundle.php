<?php

namespace Vivait\SettingsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Vivait\SettingsBundle\DependencyInjection\SettingsCompilerPass;

class VivaitSettingsBundle extends Bundle
{
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new SettingsCompilerPass());
	}
}
