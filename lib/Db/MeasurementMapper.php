<?php
/**
 * *
 *  *
 *  * @copyright Copyright (c) 2019, Nils Griebner (nils@nils-griebner.de)
 *  *
 *  * @license GNU AGPL version 3 or any later version
 *  *
 *  * This program is free software: you can redistribute it and/or modify
 *  * it under the terms of the GNU Affero General Public License as
 *  * published by the Free Software Foundation, either version 3 of the
 *  * License, or (at your option) any later version.
 *  *
 *  * This program is distributed in the hope that it will be useful,
 *  * but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  * GNU Affero General Public License for more details.
 *  *
 *  * You should have received a copy of the GNU Affero General Public License
 *  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *  *
 *
 */

namespace OCA\AsthmaDiary\Db;

use OCA\AsthmaDiary\Service\ParameterValidationException;
use OCP\AppFramework\Db\Entity;
use OCP\IDbConnection;
use OCP\AppFramework\Db\Mapper;

/**
 * Class MeasurementMapper
 *
 * @package OCA\AsthmaDiary\Db
 */
class MeasurementMapper extends Mapper {

	public function __construct(IDbConnection $db) {
		parent::__construct($db, 'asthmadiary_measurements', '\OCA\AsthmaDiary\Db\Measurement');
	}

	public function insert(Entity $entity) {

		$result = $this->findByDate(
			$entity->getDate(),
			$entity->getDate(),
			$entity->getUserId());

		if (empty($result)) {
			return parent::insert($entity);
		}

		throw new ParameterValidationException();
	}

	/**
	 * Get a measurement from db by time range
	 *
	 * @param $from
	 * @param $to
	 * @param $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 */
	public function findByDate($from, $to, $userId) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_measurements WHERE date BETWEEN ? AND ? AND user_id = ?';
		return $this->findEntities($sql, [$from, $to, $userId]);
	}

	/**
	 * Get all measurement for user foo
	 *
	 * @param $userId
	 * @return array
	 */
	public function findAll($userId) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_measurements WHERE user_id = ?';
		return $this->findEntities($sql, [$userId]);
	}

	/**
	 * Get meaasurement by id
	 *
	 * @param $id
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 */
	public function find($id) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_measurements WHERE id = ?';
		return $this->findEntity($sql, [$id]);
	}

	/**
	 * Delete a measurement from db. Delete also all values with the same
	 * date
	 *
	 * @param Entity $entity
	 * @return Entity
	 */
	public function delete(Entity $entity) {
		$valueMapper = new ValueMapper($this->db);
		$date = $entity->getDate();
		$userId = $entity->getUserId();
		$result = $valueMapper->findAllFiltered($date, $date, $userId);

		if (empty($result)) {
			return parent::delete($entity);
		} else {
			foreach ($result as $value) {
				$valueMapper->delete($value);
			}
			return parent::delete($entity);
		}
	}
}
