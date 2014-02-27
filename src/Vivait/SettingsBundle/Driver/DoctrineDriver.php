<?php
namespace Vivait\SettingsBundle\Driver;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DoctrineDriver implements ParametersStorageInterface {
	/* @var $repository \Vivait\SettingsBundle\Entity\SettingsRepository */
	private $repository;

	/* @var $entity_manager EntityManager */
	private $entity_manager;

	private $settings;

	public function __construct(EntityManager $em, EntityRepository $repository) {
		$this->repository     = $repository;
		$this->entity_manager = $em;
		$this->settings       = $this->repository->findAllIndexed();
	}

	public function has($key) {
		return (isset($this->settings[$key]));
	}

	public function get($key) {
		return (isset($this->settings[$key])) ? $this->settings[$key] : null;
	}

	public function set($key, $value) {
		// Change the stored settings array first
		$this->settings[$key] = $value;

		// Update the Doctrine object behind it
		$entity = $this->repository->find($key);

		if (!$entity) {
			$entity = $this->repository->create($key);
		}

		$entity->setValue($value);
		$this->entity_manager->persist($entity);
	}

	public function __destruct() {
		$this->entity_manager->flush();
	}

	public function remove($key) {
		if ($this->has($key)) {
			unset($this->settings[$key]);

			$entity = $this->repository->find($key);

			if ($entity) {
				$this->entity_manager->remove($entity);
			}
		}
	}
}