<?php

namespace spec\Vivait\SettingsBundle\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Vivait\SettingsBundle\Services\FormChain;
 */
class FormChainSpec extends ObjectBehavior
{
	function it_is_an_object_storage() {
		$this->shouldImplement('\SplObjectStorage');
	}
}