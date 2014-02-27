<?php

namespace spec\Vivait\SettingsBundle\Form\DataTransformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin \Vivait\SettingsBundle\Form\DataTransformer\KeyToArrayTransformer
 */
class KeyToArrayTransformerSpec extends ObjectBehavior {
	function it_should_transform_a_normal_key() {
		$this
			->transform([
				'key1' => 'value1',
				'key2' => 'value2'
			])
			->shouldReturn([
				'key1' => 'value1',
				'key2' => 'value2'
			]);
	}

	function it_should_transform_a_nested_key() {
		$this
			->transform([
				'group1.key1' => 'value1',
				'group1.key2' => 'value2'
			])
			->shouldReturn([
				'group1' => [
					'key1' => 'value1',
					'key2' => 'value2'
				]
			]);
	}

	function it_should_transform_a_multi_nested_key() {
		$this
			->transform([
				'group1.group2.key1' => 'value1',
				'group1.group2.key2' => 'value2'
			])
			->shouldReturn([
				'group1' => [
					'group2' => [
						'key1' => 'value1',
						'key2' => 'value2'
					]
				]
			]);
	}

	function it_should_transform_a_multi_nested_key_with_group_siblings() {
		$this
			->transform([
				'group1.key1' => 'value1',
				'group1.group2.key2' => 'value2'
			])
			->shouldReturn([
				'group1' => [
					'key1' => 'value1',
					'group2' => [
						'key2' => 'value2'
					]
				]
			]);
	}

	function it_should_transform_a_multi_nested_key_with_a_group_clash() {
		$this
			->transform([
				'group1' => 'value',
				'group1.key1' => 'value1',
				'group1.key2' => 'value2'
			])
			->shouldReturn([
				'group1' => [
					'key1' => 'value1',
					'key2' => 'value2'
				]
			]);
	}

	function it_should_reverse_transform_a_normal_array() {
		$this
			->reverseTransform([
				'key1' => 'value1',
				'key2' => 'value2'
			])
			->shouldReturn([
				'key1' => 'value1',
				'key2' => 'value2'
			]);
	}

	function it_should_reverse_transform_a_nested_array() {
		$this
			->reverseTransform([
				'group1' => [
					'key1' => 'value1',
					'key2' => 'value2'
				]
			])
			->shouldReturn([
				'group1.key1' => 'value1',
				'group1.key2' => 'value2'
			]);
	}

	function it_should_reverse_transform_a_multi_nested_array() {
		$this
			->reverseTransform([
				'group1' => [
					'group2' => [
						'key1' => 'value1',
						'key2' => 'value2'
					]
				]
			])
			->shouldReturn([
				'group1.group2.key1' => 'value1',
				'group1.group2.key2' => 'value2'
			]);
	}
}