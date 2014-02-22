<?php

namespace Vivait\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Vivait\SettingsBundle\Entity\Settings;
use Vivait\SettingsBundle\Form\EventListener\SettingsSubscriber;

class SettingsController extends Controller {

	public function editAction(Request $request) {
		// Get the registry
		$registry   = $this->get('vivait_settings.registry');
		$forms      = $this->get('vivait_settings.form.registry');

		// TODO: Let the request override this
		$driver    = $registry->getDefaultDriver();
		$builder   = $this->createFormBuilder();
		// TODO: Add validation
		$parts     = array();

		// Build the various parts of the form
		foreach ($forms->getDefinitions() as $type) {
			$definition = $forms->getDefinition($type);

			if ($type && $type instanceOf AbstractType) {
				$builder->add($definition['for'], $type, array(
					'label_render' => false
				));

				$parts[] = array(
					'name'  => $definition['title'],
					'alias' => $definition['for']
				);
			}
		}

		$builder->addEventSubscriber(new SettingsSubscriber());

		$form = $builder->getForm();
		$form->handleRequest($request);

		if ($form->isValid()) {
//
//			// Convert them back from key => pair to separate entities
//			foreach ($form->getData() as $service_alias => $values) {
//				foreach ($values as $alias => $value) {
//					var_dump('[' . $service_alias . '][' . $alias . ']');
//					$driver->set('['. $service_alias .']['. $alias .']', $value);
////					$obj = $repo->findOneBy(array(
////						'service_alias' => $service_alias,
////						'alias'         => $alias,
////						'tenant'        => $tenant
////					));
////
////					if (!$obj) {
////						$obj = new Settings;
////						$obj->setServiceAlias($service_alias)
////							->setTenant($tenant)
////							->setAlias($alias);
////					}
////
////					$obj->setValue($value);
////					$em->persist($obj);
//				}
//			}
//
//			$em->flush();

			$this->get('session')->getFlashBag()->add('success', 'The settings have been updated successfully!');
			return $this->redirect($request->headers->get('referer'));
		}

		//$this->createForm(new SettingsType($this->getDoctrine(), $definitions));

		return $this->render('VivaitSettingsBundle:Maintenance:settings.html.twig', array(
			'db'            => $parts,
			'form'          => $form->createView()
		));
	}
}