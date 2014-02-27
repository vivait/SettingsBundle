<?php

namespace Vivait\SettingsBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class KeyToArrayTransformer implements DataTransformerInterface
{
	public function transform($data)
	{
		$new_data = [];

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				// It contains a sub-group
				if (strpos($key, '.') !== false) {
					// Get the first part of that sub-group
					$parts = explode('.', $key, 2);

					// Transform the rest of the sub-groups and merge them with the current stack
					$new_data[$parts[0]] = array_merge_recursive(
						(isset($new_data[$parts[0]]) && is_array($new_data[$parts[0]])) ? $new_data[$parts[0]] : [],
						$this->transform([
							$parts[1] => $value
						])
					);
				}
				else {
					$new_data[$key] = $value;
				}
			}
		}

		return $new_data;
	}

	public function reverseTransform($data, $key = '')
	{
		$new_data = [];

		if (is_array($data)) {
			foreach ($data as $new_key => $value) {
				$new_data += $this->reverseTransform($value, ($key ? $key .'.' : '') . $new_key);
			}
		}
		else if ($data !== null) {
			$new_data[$key] = $data;
		}

		return $new_data;
	}
}