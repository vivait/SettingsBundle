<?php
namespace Vivait\SettingsBundle\Form\EventListener;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

class SettingsSubscriber implements EventSubscriberInterface
{
	/* @var $driver ParametersStorageInterface */
	private $driver;

	function __construct(ParametersStorageInterface $driver) {
		$this->driver = $driver;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::POST_SET_DATA => 'hydrateDataEvent'
		);
	}

	public function hydrateDataEvent(FormEvent $event) {
		foreach ($event->getForm()->all() as $child) {
			if($child instanceof Form){
				$this->hydrateData($child);
			}
		}
	}

	public function hydrateData(Form $form, $path = '')
	{
		$children = $form->all();
		$path = ($path ? $path .'.' : $path) . $form->getName();

		if (count($children)) {
			foreach ($children as $child) {
				$this->hydrateData($child, $path);
			}
		}
		else {
			if ($form->getData() === null) {
				$form->setData($this->driver->get($path));
			}
		}
	}
}