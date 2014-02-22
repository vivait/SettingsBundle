<?php
namespace Vivait\SettingsBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vivait\SettingsBundle\Driver\ParametersStorageInterface;

class SettingsSubscriber implements EventSubscriberInterface
{
	/* @var $driver ParametersStorageInterface */
	protected $driver;

	public function __construct(ParametersStorageInterface $driver = null) {
		$this->driver = $driver;
	}

	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::SUBMIT => 'Submit'
		);
	}

	public function Submit(FormEvent $event)
	{
		$groups = $event->getData();

		$this->setData($groups);
		exit;
//		foreach ($groups as $group => $data) {
//			foreach ($data as $key => $value) {
//				$this->driver->set($group .'.'. $value);
//			}
//		}

		exit;


//
//		if (!$product || null === $product->getId()) {
//			$form->add('name', 'text');
//		}
	}

	protected function setData($data, $path = '')
	{
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$this->setData($value, ($path ? $path .'.' : '') . $key);
			}
		}
		else {
			$this->driver->set($path, $value);
		}
	}
}