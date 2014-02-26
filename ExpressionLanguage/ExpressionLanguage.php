<?php

namespace Vivait\SettingsBundle\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;

class ExpressionLanguage extends BaseExpressionLanguage
{
	protected function registerFunctions()
	{
		var_dump('yay');exit;
		parent::registerFunctions(); // do not forget to also register core functions

		$php = <<<'EOL'
	$this->get(%s)->get(%s, %s);
EOL;

		$settings_alias = 'vivait_settings.registry';

		$this->register('setting',
			function ($arg, $driver = null) use ($php, $settings_alias) {
				return sprintf($php, $settings_alias, $arg, $driver);
			},
			function (array $variables, $value) {
				return $variables['container']->get($value);
			}
		);
	}
}