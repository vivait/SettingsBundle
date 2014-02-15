<?php

namespace Viva\SettingsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\Request;
use Viva\SettingsBundle\Entity\Settings;

class SettingsController extends Controller {
	public function editAction(Request $request) {
		$tenant    = $this->getUser()->getCurrentTenant();
		$em        = $this->getDoctrine()->getManager();
		$repo      = $em->getRepository('VivaSettingsBundle:Settings');
		$service   = $this->get('viva_settings.registry');
		$entity    = $service->getSettings($tenant);
		$builder   = $this->createFormBuilder($entity);
		$parts     = array();
		$validator = $this->get('validator');

		// Build the various parts of the form
		foreach ($service->getDefinitions() as $alias => $definition) {
			$type = $definition->getSettingsForm();

			if ($type && $type instanceOf AbstractType) {
				$builder->add($alias, $type, array(
					'label_render' => false
				));

				$parts[] = array(
					'name'  => $definition->getSettingsName(),
					'alias' => $alias
				);

				//$type->addValidation($validator);
			}
		}

		$form = $builder->getForm();
		$form->handleRequest($request);

		if ($form->isValid()) {

			// Convert them back from key => pair to separate entities
			foreach ($form->getData() as $service_alias => $values) {
				foreach ($values as $alias => $value) {
					$obj = $repo->findOneBy(array(
						'service_alias' => $service_alias,
						'alias'         => $alias,
						'tenant'        => $tenant
					));

					if (!$obj) {
						$obj = new Settings;
						$obj->setServiceAlias($service_alias)
							->setTenant($tenant)
							->setAlias($alias);
					}

					$obj->setValue($value);
					$em->persist($obj);
				}
			}

			$em->flush();

			$this->get('session')->getFlashBag()->add('success', 'The settings have been updated successfully!');
			return $this->redirect($request->headers->get('referer'));
		}

		//$this->createForm(new SettingsType($this->getDoctrine(), $definitions));

		return $this->render('VivaSettingsBundle:Maintenance:settings.html.twig', array(
			'tenant'        => $tenant,
			'db'            => $parts,
			'form'          => $form->createView()
		));
	}
}