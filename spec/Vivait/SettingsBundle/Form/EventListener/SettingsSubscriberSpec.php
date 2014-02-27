<?php

namespace spec\Vivait\SettingsBundle\Form\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

/**
 * @mixin \Vivait\SettingsBundle\Form\EventListener\SettingsSubscriber
 */
class SettingsSubscriberSpec extends ObjectBehavior {
	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 */
	function let(ParametersStorageInterface $driver) {
		$this->beConstructedWith($driver);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 * @param \Symfony\Component\Form\Form $form
	 */
	function it_should_hydrate_a_form_field(ParametersStorageInterface $driver, Form $form) {
		// Lets set up the forms
		$form->getName()->willReturn('form1');
		$form->all()->willReturn(array());

		// It should access the driver to get the data
		$driver->has('form1')->willReturn(true);
		$driver->get('form1')->willReturn('value1')->shouldBeCalled();

		// It should set the form's data
		$form->getData()->shouldBeCalled();
		$form->setData('value1')->shouldBeCalled();

		$this->hydrateData($form);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 * @param \Symfony\Component\Form\Form $form1
	 * @param \Symfony\Component\Form\Form $form2
	 */
	function it_should_hydrate_a_nested_form_field(ParametersStorageInterface $driver, Form $form1, Form $form2) {
		// Lets set up the nested forms
		$form1->getName()->willReturn('form1');
		$form1->all()->willReturn([
			$form2
		]);

		$form2->getName()->willReturn('form2');
		$form2->all()->willReturn(array());

		// It should access the driver to get the data
		$driver->has('form1.form2')->willReturn(true);
		$driver->get('form1.form2')->willReturn('value1')->shouldBeCalled();

		// It should set the form's data
		$form2->getData()->shouldBeCalled();
		$form2->setData('value1')->shouldBeCalled();

		$this->hydrateData($form1);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 * @param \Symfony\Component\Form\Form $form1
	 * @param \Symfony\Component\Form\Form $form2
	 * @param \Symfony\Component\Form\Form $form3
	 */
	function it_should_hydrate_a_multi_level_nested_form_field(ParametersStorageInterface $driver, Form $form1, Form $form2, Form $form3) {
		// Lets set up the multi-level nested forms
		$form1->getName()->willReturn('form1');
		$form1->all()->willReturn([
			$form2
		]);

		$form2->getName()->willReturn('form2');
		$form2->all()->willReturn(array(
			$form3
		));

		$form3->getName()->willReturn('form3');
		$form3->all()->willReturn(array());

		// It should access the driver to get the data
		$driver->has('form1.form2.form3')->willReturn(true);
		$driver->get('form1.form2.form3')->willReturn('value1')->shouldBeCalled();

		// It should set the form's data
		$form3->getData()->shouldBeCalled();
		$form3->setData('value1')->shouldBeCalled();

		$this->hydrateData($form1);
	}

	/**
	 * @param \Vivait\SettingsBundle\Driver\ParametersStorageInterface $driver
	 * @param \Symfony\Component\Form\Form $form1
	 * @param \Symfony\Component\Form\Form $form2
	 * @param \Symfony\Component\Form\Form $form3
	 */
	function it_should_hydrate_all_nested_form_fields(ParametersStorageInterface $driver, Form $form1, Form $form2, Form $form3) {
		// Lets set up the nested forms
		$form1->getName()->willReturn('form1');
		$form1->all()->willReturn([
			$form2,
			$form3
		]);

		$form2->getName()->willReturn('form2');
		$form2->all()->willReturn(array());

		$form3->getName()->willReturn('form3');
		$form3->all()->willReturn(array());

		// It should access the driver to get the data
		$driver->get('form1.form2')->shouldBeCalled();
		$driver->get('form1.form3')->shouldBeCalled();

		$driver->has('form1.form2')->willReturn(true);
		$driver->get('form1.form2')->willReturn('value1')->shouldBeCalled();

		$driver->has('form1.form3')->willReturn(true);
		$driver->get('form1.form3')->willReturn('value2')->shouldBeCalled();

		// It should set both the forms' data
		$form2->getData()->shouldBeCalled();
		$form2->setData('value1')->shouldBeCalled();

		$form3->getData()->shouldBeCalled();
		$form3->setData('value2')->shouldBeCalled();

		$this->hydrateData($form1);
	}
}