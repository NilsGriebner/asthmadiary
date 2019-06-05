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

use OCA\AsthmaDiary\Service\NotFoundException;
use OCP\AppFramework\Db\Entity;
use OCP\IDbConnection;
use OCP\AppFramework\Db\Mapper;

class ValueMapper extends Mapper {

	public function __construct(IDbConnection $db) {
		parent::__construct($db, 'asthmadiary_values', '\OCA\AsthmaDiary\Db\Value');
	}

	public function insert(Entity $entity) {

		/**
		 * If measurement exists for this date, insert value, else throw
		 * Exception
		 */
		$measurementMapper = new MeasurementMapper($this->db);
		$date = $entity->getDate();
		$userId = $entity->getUserId();
		$time = $entity->getTime();
		$result = $measurementMapper->findByDate($date, $date, $userId);

		if (empty($result)) {
			throw new NotFoundException();
		}

		$result = $this->findByDateAndTime($date, $time, $userId);

		if(count($result) !== 0) {
			throw new NotFoundException();
		}

		return parent::insert($entity);
	}

	/**
	 * Get value by id and userId
	 *
	 * @param $id
	 * @param $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 */
	public function find($id, $userId) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_values WHERE id = ? AND user_id = ?';
		return $this->findEntity($sql, [$id, $userId]);
	}

	/**
	 * Get all values for user foo
	 *
	 * @param $userId
	 * @return array
	 */
	public function findAll($userId) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_values WHERE user_id = ?';
		return $this->findEntities($sql, [$userId]);
	}

	/**
	 * Get all values for user foo between dates
	 *
	 * @param $from
	 * @param $to
	 * @param $userId
	 * @return array
	 */
	public function findAllFiltered($from, $to, $userId) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_values WHERE date BETWEEN  ? AND ? AND user_id = ?';
		return $this->findEntities($sql, [$from, $to, $userId]);
	}

	/**
	 * Get value by date and time
	 *
	 * @param $from
	 * @param $to
	 * @param $userId
	 * @return array
	 */
	public function findByDateAndTime($date, $time, $userId) {
		$sql = 'SELECT * FROM *PREFIX*asthmadiary_values WHERE date = ? AND time = ? AND user_id = ?';
		return $this->findEntities($sql, [$date, $time, $userId]);
	}
}
