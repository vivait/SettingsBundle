<?php
namespace Vivait\SettingsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class SettingsRepository extends EntityRepository {
	public function findAllIndexed() {
		$query = $this->createQueryBuilder('s')
			->select('s.id, s.value')
			->add('from', new Expr\From('VivaitSettingsBundle:Settings', 's', 's.id'), false)
			->getQuery();

		return array_map(function($data) {
			return $data['value'];
		}, $query->getArrayResult());
	}

	public function create() {
		return new Settings();
	}
}