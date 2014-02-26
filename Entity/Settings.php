<?php

namespace Vivait\SettingsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="\Vivait\SettingsBundle\Entity\SettingsRepository")
 */
class Settings {
	/**
	 * @var string
	 *
	 * @ORM\Column(name="id", type="string", length=100)
	 * @ORM\Id
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="value", type="string", length=255, nullable=true)
	 */
	private $value;

	/**
	 * @ORM\ManyToOne(targetEntity="\Vivait\AuthBundle\Entity\Tenant", inversedBy="settings")
	 */
	private $tenant;

	/**
	 * Constructor
	 */
	public function __construct($id = null) {
		$this->id      = $id;
		$this->tenants = new ArrayCollection();
	}

	/**
	 * Sets id
	 * @param string $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this->id;
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
	 * Set tenant
	 *
	 * @param \Vivait\AuthBundle\Entity\Tenant $tenant
	 * @return Settings
	 */
	public function setTenant(\Vivait\AuthBundle\Entity\Tenant $tenant = null) {
		$this->tenant = $tenant;

		return $this;
	}

	/**
	 * Get tenant
	 *
	 * @return \Vivait\AuthBundle\Entity\Tenant
	 */
	public function getTenant() {
		return $this->tenant;
	}
}