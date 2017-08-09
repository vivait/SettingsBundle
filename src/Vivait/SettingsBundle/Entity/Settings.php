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
	 * @ORM\Column(name="value", type="text", nullable=true)
	 */
	private $value;

	/**
	 * Constructor
	 */
	public function __construct($id = null) {
		$this->id      = $id;
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
     * @param $value
     * @return $this
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
}
