<?php

namespace Vivait\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vivait\SettingsBundle\Entity\Settings;
use Vivait\SettingsBundle\Form\DataTransformer\KeyToArrayTransformer;
use Vivait\SettingsBundle\Form\EventListener\SettingsSubscriber;
use Vivait\SettingsBundle\Form\Type\SettingsType;

class SettingsController extends Controller {

	public function editAction(Request $request) {
		// Get the forms
		$forms        = $this->get('vivait_settings.registry.forms');
		$driver       = $this->get('vivait_settings.driver.doctrine');
		$settingsType = new SettingsType($driver, $forms);

		$form = $this->createForm($settingsType);

		$form->handleRequest($request);

		if ($form->isValid()) {
			foreach ($form->getData() as $key => $value) {
				$driver->set($key, $value);
			}

			$this->get('session')->getFlashBag()->add('success', 'The settings have been updated successfully!');
			return $this->redirect($request->headers->get('referer'));
		}

		return $this->render('VivaitSettingsBundle:Maintenance:settings.html.twig', array(
			'areas' => $settingsType->getAreas(),
			'form'  => $form->createView()
		));
	}
}
