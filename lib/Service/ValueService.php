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

namespace OCA\AsthmaDiary\Service;

use DateTime;
use Exception;
use OCA\AsthmaDiary\Db\Value;
use OCA\AsthmaDiary\Db\ValueMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;


class ValueService {

	private $mapper;

	public function __construct(ValueMapper $mapper) {
		$this->mapper = $mapper;
	}

	/**
	 * Get all values for user foo. If $from and $to are provided, get
	 * values between
	 *
	 * @param string $from A date in format YYYY-MM-DD
	 * @param string $to A date in format YYYY-MM-DD
	 * @param string $userId
	 * @return array
	 */
	public function findAll($from, $to, $userId) {
		if (isset($from) && isset($to)) {
			return $this->mapper->findAllFiltered($from, $to, $userId);
		} else {
			return $this->mapper->findAll($userId);
		}
	}

	/**
	 * Handle exceptions
	 *
	 * @param Exception $e
	 * @throws NotFoundException
	 */
	private function handleException($e) {
		if ($e instanceof DoesNotExistException ||
			$e instanceof MultipleObjectsReturnedException) {
			throw new NotFoundException($e->getMessage());
		} else {
			throw $e;
		}
	}

	/**
	 * Get value by id
	 *
	 * @param integer $id
	 * @param string $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws NotFoundException
	 */
	public function find($id, $userId) {
		try {
			return $this->mapper->find($id, $userId);

			// in order to be able to plug in different storage backends like files
			// for instance it is a good idea to turn storage related exceptions
			// into service related exceptions so controllers and service users
			// have to deal with only one type of exception
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * Create value
	 *
	 * @param string $date A date in format YYYY-MM-DD
	 * @param string $time A time in format HH:MM:SS
	 * @param string $value
	 * @param string $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws ParameterValidationException
	 */
	public function create($date, $time, $value, $userId) {
		try {
			$this->validateDate($date);
			$this->validateTime($time);
			$this->validateValue($value);
			$this->validateUserId($userId);

			$newValue = new Value();
			$newValue->setDate($date);
			$newValue->setTime($time);
			$newValue->setValue($value);
			$newValue->setUserId($userId);

			return $this->mapper->insert($newValue);
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	/**
	 * Update existing value
	 *
	 * @param int $id
	 * @param string $date A date in format YYYY-MM-DD
	 * @param string $time A time in format HH:MM:SS
	 * @param string $value
	 * @param string $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws NotFoundException
	 */
	public function update($id, $date, $time, $value, $userId) {
		try {
			$this->validateId($id);
			$this->validateDate($date);
			$this->validateTime($time);
			$this->validateValue($value);
			$this->validateUserId($userId);

			$newValue = $this->find($id, $userId);
			$newValue->setDate($date);
			$newValue->setTime($time);
			$newValue->setValue($value);

			return $this->mapper->update($newValue);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * Delete value
	 *
	 * @param int $id
	 * @param string $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws NotFoundException
	 */
	public function delete($id, $userId) {
		try {
			$value = $this->mapper->find($id, $userId);
			$this->mapper->delete($value);
			return $value;
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * Check if date is valid
	 *
	 * @param $date
	 * @throws ParameterValidationException
	 */
	private function validateDate($date) {
		if ($date === null || !is_string($date)) {
			throw new ParameterValidationException();
		}

		$dt = DateTime::createFromFormat("Y-m-d", $date);

		if ($dt === false) {
			throw new ParameterValidationException();
		}

		if (gettype($dt) === 'array') {
			if (count($dt['errors']) !== 0) {
				throw new ParameterValidationException();
			}
		}
	}

	/**
	 * Check if id is valid
	 *
	 * @param $id
	 * @throws ParameterValidationException
	 */
	private function validateId($id) {
		if ($id === null || !is_numeric($id)) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Check if time is valid
	 *
	 * @param $time
	 * @throws ParameterValidationException
	 */
	private function validateTime($time) {
		if ($time === null || !is_string($time)) {
			throw new ParameterValidationException();
		}

		$dt = DateTime::createFromFormat("H:i:s", $time);

		if ($dt === false) {
			throw new ParameterValidationException();
		}

		if (gettype($dt) === 'array') {
			if (count($dt['errors']) !== 0) {
				throw new ParameterValidationException();
			}
		}
	}

	/**
	 * Check if  value is valid
	 *
	 * @param $value
	 * @throws ParameterValidationException
	 */
	private function validateValue($value) {
		if ($value === null ||
			!is_numeric($value) ||
			$value < 1 ||
			$value > 900) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Check if user_id is valid
	 *
	 * @param $userId
	 * @throws ParameterValidationException
	 */
	private function validateUserId($userId) {
		if ($userId === null || !is_String($userId)) {
			throw new ParameterValidationException();
		}
	}
}