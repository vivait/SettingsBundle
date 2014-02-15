<?php
namespace Viva\SettingsBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Viva\AuthBundle\Entity\Tenant;
use Viva\AuthBundle\EventListener\TenantManager;
use Viva\SettingsBundle\Interfaces\Settings;

class SettingsService {
	protected $definitions = array();
	protected $settings;

	/* @var $logger Logger  */
	protected $logger;

	/* @var $entity_manager Registry  */
	protected $entity_manager;

	/* @var $tenant_manager TenantManager */
	protected $tenant_manager;

	public function __construct(Registry $em, Logger $logger, TenantManager $tenant_manager) {
		$this->entity_manager = $em;
		$this->logger         = $logger;
		$this->tenant_manager = $tenant_manager;
	}

	public function addDefinition(Settings $definition, $alias)
	{
		if ($definition instanceOf Settings) {
			$definition->setSettingsRegistry($this);
		}
		
		$this->definitions[$alias] = $definition;

		return $this;
	}

	/**
	 * Returns a defined service
	 * @param $alias string The service alias
	 * @return null|object
	 */
	public function getDefinition($alias)
	{
		if (array_key_exists($alias, $this->definitions)) {
			return $this->definitions[$alias];
		}

		return null;
	}

	/**
	 * Returns the defined services
	 * @return Settings[]
	 */
	public function getDefinitions()
	{
		return $this->definitions;
	}

	/**
	 * @param $tenant
	 * @return array The settings array grouped by service alias
	 */
	public function getSettings(Tenant $tenant = null) {
		if (!$tenant) {
			$tenant = $this->tenant_manager->getTenant();
		}

		if (!$tenant) {
			// TODO: Make me throw an exception
			$this->logger->error('No tenant provided');
			return array();
		}

		$tenant_id = $tenant->getId();

		// We've already loaded the settings
		if ($this->settings[$tenant_id] !== null) {
			return $this->settings[$tenant_id];
		}

		// Get all of the settings
		$settings = $this->entity_manager
			->getRepository('VivaSettingsBundle:Settings')
			->findByTenant($tenant);

		$entity = array();

		/* @var $setting \Viva\SettingsBundle\Entity\Settings */
		foreach ($settings as $setting) {
			$entity
				[$setting->getServiceAlias()]
				[$setting->getAlias()] = $setting->getValue();
		}

		return $this->settings[$tenant_id] = $entity;
	}

	public function get($name, $service_alias, $tenant = null) {
		$settings = $this->getSettings($tenant);

		if (isset($settings[$service_alias][$name])) {
			return $settings[$service_alias][$name];
		}

		$this->logger->error(sprintf('No setting "%s" found for service "%s"', $name, $service_alias));

		return null;
	}

	/**
	 * @return EntityManager
	 */
	protected function getEntityManager() {
		return $this->entity_manager;
	}
}