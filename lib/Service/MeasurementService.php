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
use OCA\AsthmaDiary\Db\Measurement;
use OCA\AsthmaDiary\Db\MeasurementMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;


class MeasurementService {

	private $mapper;

	public function __construct(MeasurementMapper $mapper) {
		$this->mapper = $mapper;
	}

	/**
	 * Get all measurements by user. If $from and $to are provided, get measurements
	 * between dates
	 *
	 * @param $from
	 * @param $to
	 * @param $userId
	 * @return array|\OCP\AppFramework\Db\Entity
	 * @throws DoesNotExistException
	 * @throws MultipleObjectsReturnedException
	 */
	public function findAll($from, $to, $userId) {
		try {
			if (isset($from) && isset($to)) {
				return $this->mapper->findByDate($from, $to, $userId);
			} else {
				return $this->mapper->findAll($userId);
			}
		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	/**
	 * Handle different exceptions
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
	 * Get measurement
	 *
	 * @param $id
	 * @param $userId
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
	 * Create new measurement
	 *
	 * @param $userId
	 * @param $date
	 * @param $cough
	 * @param $breathlessness
	 * @param $phlegm
	 * @param $other_symptoms
	 * @param $prn_medication_puffs
	 * @param $medication1
	 * @param $dose1
	 * @param $medication2
	 * @param $dose2
	 * @param $medication3
	 * @param $dose3
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws NotFoundException
	 */
	public function create($userId,
						   $date,
						   $cough,
						   $breathlessness,
						   $phlegm,
						   $other_symptoms,
						   $prn_medication_puffs,
						   $medication1,
						   $dose1,
						   $medication2,
						   $dose2,
						   $medication3,
						   $dose3) {

		try {

			$this->validateInputDependencies($medication1,
				$dose1,
				$medication2,
				$dose2,
				$medication3,
				$dose3);


			$this->validateDate($date);
			$this->validateUserId($userId);

			if ($medication1 !== null) {
				$this->validateTextInput($medication1);
				$this->validateTextInputLength($medication1);
			}

			if ($medication2 !== null) {
				$this->validateTextInput($medication2);
				$this->validateTextInputLength($medication2);
			}

			if ($medication3 !== null) {
				$this->validateTextInput($medication3);
				$this->validateTextInputLength($medication3);
			}

			if ($dose1 !== null) {
				$this->validateTextInput($dose1);
				$this->validateTextInputLength($dose1);
			}

			if ($dose2 !== null) {
				$this->validateTextInput($dose2);
				$this->validateTextInputLength($dose2);
			}

			if ($dose3 !== null) {
				$this->validateTextInput($dose3);
				$this->validateTextInputLength($dose3);
			}

			$this->validateInputDependencies($medication1,
				$dose1,
				$medication2,
				$dose2,
				$medication3,
				$dose3
			);

			if ($cough !== null) {
				$this->validateParameterRange($cough);
			} else {
				$cough = 0;
			}

			if ($breathlessness !== null) {
				$this->validateParameterRange($breathlessness);
			} else {
				$breathlessness = 0;
			}

			if ($phlegm !== null) {
				$this->validateParameterRange($phlegm);
			} else {
				$phlegm = 0;
			}

			if ($other_symptoms !== null) {
				$this->validateOtherSymptoms($other_symptoms);
				$this->validateTextInputLength($other_symptoms);

			}

			if ($prn_medication_puffs !== null) {
				$this->validatePrnMedicationPuffs($prn_medication_puffs);
			} else {
				$prn_medication_puffs = 0;
			}

			$newMeasurement = new Measurement();
			$newMeasurement->setUserId($userId);
			$newMeasurement->setDate($date);
			$newMeasurement->setDate($date);
			$newMeasurement->setCough($cough);
			$newMeasurement->setBreathlessness($breathlessness);
			$newMeasurement->setPhlegm($phlegm);
			$newMeasurement->setOtherSymptoms($other_symptoms);
			$newMeasurement->setPrnMedicationPuffs($prn_medication_puffs);
			$newMeasurement->setMedication1($medication1);
			$newMeasurement->setDose1($dose1);
			$newMeasurement->setMedication2($medication2);
			$newMeasurement->setDose2($dose2);
			$newMeasurement->setMedication3($medication3);
			$newMeasurement->setDose3($dose3);
			return $this->mapper->insert($newMeasurement);

		} catch (Exception $e) {
			$this->handleException($e);
		}

	}

	/**
	 * Update existing measurement
	 *
	 * @param $id
	 * @param $userId
	 * @param $date
	 * @param $cough
	 * @param $breathlessness
	 * @param $phlegm
	 * @param $other_symptoms
	 * @param $prn_medication_puffs
	 * @param $medication1
	 * @param $dose1
	 * @param $medication2
	 * @param $dose2
	 * @param $medication3
	 * @param $dose3
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws NotFoundException
	 */
	public function update($id,
						   $userId,
						   $date,
						   $cough,
						   $breathlessness,
						   $phlegm,
						   $other_symptoms,
						   $prn_medication_puffs,
						   $medication1,
						   $dose1,
						   $medication2,
						   $dose2,
						   $medication3,
						   $dose3) {
		try {

			$this->validateDate($date);
			$this->validateUserId($userId);
			$this->validateId($id);

			if ($medication1 !== null) {
				$this->validateTextInput($medication1);
				$this->validateTextInputLength($medication1);
			}

			if ($medication2 !== null) {
				$this->validateTextInput($medication2);
				$this->validateTextInputLength($medication2);
			}

			if ($medication3 !== null) {
				$this->validateTextInput($medication3);
				$this->validateTextInputLength($medication3);
			}

			if ($dose1 !== null) {
				$this->validateTextInput($dose1);
				$this->validateTextInputLength($dose1);
			}

			if ($dose2 !== null) {
				$this->validateTextInput($dose2);
				$this->validateTextInputLength($dose2);
			}

			if ($dose3 !== null) {
				$this->validateTextInput($dose3);
				$this->validateTextInputLength($dose3);
			}

			$this->validateInputDependencies($medication1,
				$dose1,
				$medication2,
				$dose2,
				$medication3,
				$dose3
			);

			if ($cough !== null) {
				$this->validateParameterRange($cough);
			} else {
				$cough = 0;
			}

			if ($breathlessness !== null) {
				$this->validateParameterRange($breathlessness);
			} else {
				$breathlessness = 0;
			}

			if ($phlegm !== null) {
				$this->validateParameterRange($phlegm);
			} else {
				$phlegm = 0;
			}

			if ($other_symptoms !== null) {
				$this->validateOtherSymptoms($other_symptoms);
				$this->validateTextInputLength($other_symptoms);

			}

			if ($prn_medication_puffs !== null) {
				$this->validatePrnMedicationPuffs($prn_medication_puffs);
			} else {
				$prn_medication_puffs = 0;
			}

			/**
			 * Not using strval for inserting Cough, Breathlessness, Phlegm
			 * and prn will result in failing tests, because find function returns
			 * these values as string, so they will show up as updated fields
			 * because they are ints until here
			 */
			$newMeasurement = $this->find($id, $userId);
			$newMeasurement->setDate($date);
			$newMeasurement->setCough(strval($cough));
			$newMeasurement->setBreathlessness(strval($breathlessness));
			$newMeasurement->setPhlegm(strval($phlegm));
			$newMeasurement->setOtherSymptoms($other_symptoms);
			$newMeasurement->setPrnMedicationPuffs(strval($prn_medication_puffs));
			$newMeasurement->setMedication1($medication1);
			$newMeasurement->setDose1($dose1);
			$newMeasurement->setMedication2($medication2);
			$newMeasurement->setDose2($dose2);
			$newMeasurement->setMedication3($medication3);
			$newMeasurement->setDose3($dose3);
			return $this->mapper->update($newMeasurement);
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * Delete measurement from db
	 *
	 * @param $id
	 * @param $userId
	 * @return \OCP\AppFramework\Db\Entity
	 * @throws NotFoundException
	 */
	public function delete($id, $userId) {
		try {
			$measurement = $this->mapper->find($id, $userId);
			$this->mapper->delete($measurement);
			return $measurement;
		} catch (Exception $e) {
			$this->handleException($e);
		}
	}

	/**
	 * Validate id field
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
	 * Validate date field
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
	 * Validate user_id field
	 *
	 * @param $userId
	 * @throws ParameterValidationException
	 */
	private function validateUserId($userId) {
		if ($userId === null || !is_string($userId)) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Ensure that breathlessness, cough and phlegm is between 0 - 3
	 *
	 * @param $value
	 * @throws ParameterValidationException
	 */
	private function validateParameterRange($value) {

		if (!is_numeric($value)) {
			throw new ParameterValidationException();
		}
		if ($value < 0 || $value > 3) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Ensure text inputs dont contain invalid characters
	 *
	 * @param $text
	 * @throws ParameterValidationException
	 */
	private function validateTextInput($text) {
		if (preg_match('/^[a-zA-Z0-9ÄÖÜäöü ]+$/', $text) === 0 ||
			!is_string($text)) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Ensure other symptoms input contains valid chars
	 *
	 * @param $text
	 * @throws ParameterValidationException
	 */
	private function validateOtherSymptoms($text) {
		if (preg_match('/^[a-zA-Z0-9ÄÖÜäöü,. ]+$/', $text) === 0 ||
			!is_string($text)) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Ensure prn is int and in range
	 *
	 * @param $prn
	 * @throws ParameterValidationException
	 */
	private function validatePrnMedicationPuffs($prn) {
		if (!is_int($prn) || $prn > 100 || $prn < 0) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Ensure text inputs stay <= 100 chars
	 *
	 * @param $text
	 * @throws ParameterValidationException
	 */
	private function validateTextInputLength($text) {
		if (strlen($text) > 100) {
			throw new ParameterValidationException();
		}
	}

	/**
	 * Ensure text field which depend on each other are filled out
	 *
	 * @param $medication1
	 * @param $dose1
	 * @param $medication2
	 * @param $dose2
	 * @param $medication3
	 * @param $dose3
	 * @throws ParameterValidationException
	 */
	private function validateInputDependencies($medication1,
											   $dose1,
											   $medication2,
											   $dose2,
											   $medication3,
											   $dose3) {

		if ($medication1 !== null) {
			if ($dose1 === null) {
				throw new ParameterValidationException();
			}
		}

		if ($dose1 !== null) {
			if ($medication1 === null) {
				throw new ParameterValidationException();
			}
		}

		if ($medication2 !== null) {
			if ($dose2 === null) {
				throw new ParameterValidationException();
			}
		}

		if ($dose2 !== null) {
			if ($medication2 === null) {
				throw new ParameterValidationException();
			}
		}

		if ($medication3 !== null) {
			if ($dose3 === null) {
				throw new ParameterValidationException();
			}
		}

		if ($dose3 !== null) {
			if ($medication3 === null) {
				throw new ParameterValidationException();
			}
		}
	}

}