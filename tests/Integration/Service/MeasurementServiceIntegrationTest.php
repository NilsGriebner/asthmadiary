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

namespace OCA\AsthmaDiary\Tests\Integration\Controller;

use OCA\AsthmaDiary\Service\MeasurementService;
use OCA\AsthmaDiary\Service\ParameterValidationException;
use OCP\AppFramework\App;
use Test\TestCase;

/**
 * @group DB
 */
class MeasurementServiceIntegrationTest extends TestCase {

	private $controller;
	private $mapper;
	private $service;
	private $userId = 'john';
	private $validId = 0;
	private $outOfRangeHigh = 4;
	private $outOfRangeLow = -1;
	private $validSelect = 0;
	private $validDate = "2014-05-04";
	private $invalidDate = "20144-05-04";
	private $medication1Valid = "medication1";
	private $medication2Valid = "medication3";
	private $medication3Valid = "medication3";
	private $medication1Invalid = "!medication1";
	private $medication2Invalid = "!medication2";
	private $medication3Invalid = "!medication3";
	private $dose1Valid = "dose1";
	private $dose2Valid = "dose2";
	private $dose3Valid = "dose3";
	private $dose1Invalid = "dos#e1";
	private $dose2Invalid = "dos#e2";
	private $dose3Invalid = "dos#e3";
	private $otherSymptomsValid = "other symptoms";
	private $otherSymptomsInvalid = "other * symptoms";
	private $prnMedicationPuffsValid = 1;
	private $prnMedicationPuffsInvalidHigh = 101;
	private $prnMedicationPuffsInvalidLow = -1;
	private $stringOutOfRange = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

	public function setUp() {
		parent::setUp();
		$app = new App('asthmadiary');
		$container = $app->getContainer();

		// only replace the user id
		$container->registerService('UserId', function ($c) {
			return $this->userId;
		});

		$this->controller = $container->query(
			'OCA\AsthmaDiary\Controller\MeasurementController'
		);

		$this->mapper = $container->query(
			'OCA\AsthmaDiary\Db\MeasurementMapper'
		);

		$this->service = new MeasurementService($this->mapper);
	}

	public function testCreateUserIdMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			null,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication2Valid,
			$this->dose2Valid);
	}

	public function testCreateUserIdWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			0,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDateMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			null,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDateWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			0,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDateInvalid() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->invalidDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateCoughWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			"test",
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	/**
	 * Dont do this for breathlessness and phlegm aggain, because they use
	 * same validation function
	 */
	public function testCreateCoughOutOfRangeHigh() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->outOfRangeHigh,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	/**
	 * Dont do this for breathlessness and phlegm aggain, becaus they use
	 * same validation function
	 */
	public function testCreateCoughOutOfRangeLow() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->outOfRangeLow,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateBreathlessnessWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			"test",
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreatePhlegmWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			"test",
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateOtherSymptomsWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			"test",
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			0,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateOtherSymptomsOutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->stringOutOfRange,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateOtherSymptomsNotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsInvalid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreatePrnMedicationPuffsWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			"test",
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreatePrnMedicationPuffsOutOfRangeLow() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsInvalidLow,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreatePrnMedicationPuffsOutOfRangeHigh() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsInvalidHigh,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication1OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->stringOutOfRange,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication1WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			0,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication1NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			"test",
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Invalid,
			$this->dose1Valid,
			$this->medication1Invalid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication2OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->stringOutOfRange,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication2WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			0,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication2NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Invalid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateMedication3OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->stringOutOfRange,
			$this->dose3Valid);
	}

	public function testCreateMedication3WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			0,
			$this->dose3Valid);
	}

	public function testCreateMedication3NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Invalid,
			$this->dose3Valid);
	}

	public function testCreateDose1OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->stringOutOfRange,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDose1WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			0,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDose1NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Invalid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDose2OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->stringOutOfRange,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDose2WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			0,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDose2NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Invalid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testCreateDose3OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->stringOutOfRange);
	}

	public function testCreateDose3WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			0);
	}

	public function testCreateDose3NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Invalid);
	}

	public function testCreateMedicationWithoutDose() {
		$this->expectException(ParameterValidationException::class);
		$this->service->create(
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			null,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	/**
	 * Test for update function
	 */
	public function testUpdateUserIdMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			null,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateUserIdWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			0,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDateMissing() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			null,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDateWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			0,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateCoughWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			"test",
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	/**
	 * Dont do this for breathlessness and phlegm aggain, becaus they use
	 * same validation function
	 */
	public function testUpdateCoughOutOfRangeHigh() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->outOfRangeHigh,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	/**
	 * Dont do this for breathlessness and phlegm aggain, becaus they use
	 * same validation function
	 */
	public function testUpdateCoughOutOfRangeLow() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->outOfRangeLow,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateBreathlessnessWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			"test",
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdatePhlegmWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			"test",
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateOtherSymptomsWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			0,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateOtherSymptomsOutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->stringOutOfRange,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateOtherSymptomsNotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsInvalid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdatePrnMedicationPuffsWrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			"test",
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdatePrnMedicationPuffsOutOfRangeLow() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsInvalidLow,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdatePrnMedicationPuffsOutOfRangeHigh() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsInvalidHigh,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateMedication1OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->stringOutOfRange,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateMedication1WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			0,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateMedication1NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Invalid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testupdateMedication2OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->stringOutOfRange,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateMedication2WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			0,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateMedication2NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Invalid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateMedication3OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->stringOutOfRange,
			$this->dose3Valid);
	}

	public function testUpdateMedication3WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			0,
			$this->dose3Valid);
	}

	public function testUpdateMedication3NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Invalid,
			$this->dose3Valid);
	}

	public function testUpdateDose1OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->stringOutOfRange,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDose1WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			0,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDose1NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Invalid,
			$this->medication2Valid,
			$this->dose2Valid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDose2OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->stringOutOfRange,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDose2WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			0,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDose2NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Invalid,
			$this->medication3Valid,
			$this->dose3Valid);
	}

	public function testUpdateDose3OutOfRange() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Invalid,
			$this->medication3Valid,
			$this->stringOutOfRange);
	}

	public function testUpdateDose3WrongType() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Invalid,
			$this->medication3Valid,
			0);
	}

	public function testUpdateDose3NotAllowedChar() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			$this->medication1Valid,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Invalid,
			$this->medication3Valid,
			$this->dose3Invalid);
	}

	public function testUpdateMedicationWithoutDose() {
		$this->expectException(ParameterValidationException::class);
		$this->service->update(
			$this->validId,
			$this->userId,
			$this->validDate,
			$this->validSelect,
			$this->validSelect,
			$this->validSelect,
			$this->otherSymptomsValid,
			$this->prnMedicationPuffsValid,
			null,
			$this->dose1Valid,
			$this->medication2Valid,
			$this->dose2Invalid,
			$this->medication3Valid,
			$this->dose3Invalid);
	}

}
