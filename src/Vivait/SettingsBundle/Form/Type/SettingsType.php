<?php

namespace Vivait\SettingsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;
use Vivait\SettingsBundle\Form\DataTransformer\KeyToArrayTransformer;
use Vivait\SettingsBundle\Form\EventListener\SettingsSubscriber;

class SettingsType extends AbstractType {
	private $forms = array();
	private $areas = array();

	/* @var $driver ParametersStorageInterface */
	private $driver;

	function __construct($driver, $forms = array()) {
		$this->driver = $driver;
		$this->forms  = $forms;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// Build the various parts of the form
		foreach ($this->forms as $type) {
			$definition = $this->forms->getInfo();

			if ($type && $type instanceOf AbstractType) {
				$builder->add($definition['for'], $type, array(
					'label_render' => false
				));

				$this->areas[] = $definition;
			}
		}

		$builder->addEventSubscriber(new SettingsSubscriber($this->driver));
		$builder->addModelTransformer(new KeyToArrayTransformer());
	}

	/**
	 * Gets areas
	 * @return array
	 */
	public function getAreas() {
		return $this->areas;
	}

	/**
	 * Sets forms
	 * @param array $forms
	 * @return $this
	 */
	public function setForms($forms) {
		$this->forms = $forms;
		return $this;
	}

	/**
	 * Gets forms
	 * @return array
	 */
	public function getForms() {
		return $this->forms;
	}

	public function getName() {
		return 'vivait_settings_type';
	}
} 