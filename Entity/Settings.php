<?php

namespace Vivait\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Settings {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alias", type="string", length=100)
	 */
	private $alias;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="service_alias", type="string", length=100)
	 */
	private $service_alias;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", length=255, nullable=true)
	 */
	private $value;

	/**
	 * @ORM\ManyToOne(targetEntity="\Viva\AuthBundle\Entity\Tenant", inversedBy="settings")
	 */
	private $tenant;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set alias
	 *
	 * @param string $alias
	 * @return Settings
	 */
	public function setAlias($alias) {
		$this->alias = $alias;

		return $this;
	}

	/**
	 * Get alias
	 *
	 * @return string
	 */
	public function getAlias() {
		return $this->alias;
	}

	/**
	 * Set serviceAlias
	 *
	 * @param string $serviceAlias
	 * @return Settings
	 */
	public function setServiceAlias($service_alias) {
		$this->service_alias = $service_alias;

		return $this;
	}

	/**
	 * Get serviceAlias
	 *
	 * @return string
	 */
	public function getServiceAlias() {
		return $this->service_alias;
	}

	/**
	 * Sets value
	 * @param string $value
	 */
	public function setValue($value) {
		$this->value = $value;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tenants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set tenant
     *
     * @param \Viva\AuthBundle\Entity\Tenant $tenant
     * @return Settings
     */
    public function setTenant(\Viva\AuthBundle\Entity\Tenant $tenant = null)
    {
        $this->tenant = $tenant;
    
        return $this;
    }

    /**
     * Get tenant
     *
     * @return \Viva\AuthBundle\Entity\Tenant 
     */
    public function getTenant()
    {
        return $this->tenant;
    }
}