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

use PHPUnit\Framework\TestCase;
use OCA\AsthmaDiary\Db\Measurement;
use OCA\AsthmaDiary\Db\Value;
use OCA\AsthmaDiary\Service\ParameterValidationException;
use OCP\AppFramework\App;
use OCP\AppFramework\Db\DoesNotExistException;

/**
 * @group DB
 */
class MeasurementIntegrationTest extends TestCase {

	private $controller;
	private $measurementMapper;
	private $valueMapper;
	private $userId = 'john';
	private $date = '2012-02-01';
	private $cough = 1;
	private $phlegm = 1;
	private $breathlessness = 1;
	private $medication1 = "med1";
	private $medication2 = "med2";
	private $medication3 = "med3";
	private $dose1 = "dose1";
	private $dose2 = "dose2";
	private $dose3 = "dose3";
	private $otherSymptoms = "other symptoms";
	private $prnMedicationPuffs = 1;

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

		$this->measurementMapper = $container->query(
			'OCA\AsthmaDiary\Db\MeasurementMapper'
		);

		$this->valueMapper = $container->query(
			'OCA\AsthmaDiary\Db\ValueMapper'
		);
	}

	public function testUpdate() {
		$measurement = new Measurement();
		$measurement->setDate($this->date);
		$measurement->setUserId($this->userId);
		$measurement->setCough($this->cough);
		$measurement->setBreathlessness($this->breathlessness);
		$measurement->setPhlegm($this->phlegm);
		$measurement->setOtherSymptoms($this->otherSymptoms);
		$measurement->setPrnMedicationPuffs($this->prnMedicationPuffs);
		$measurement->setMedication1($this->medication1);
		$measurement->setDose1($this->dose1);
		$measurement->setMedication2($this->medication2);
		$measurement->setDose2($this->dose2);
		$measurement->setMedication3($this->medication3);
		$measurement->setDose3($this->dose3);

		$id = $this->measurementMapper->insert($measurement)->getId();

		$updatedMeasurement = Measurement::fromRow([
			'id' => $id,
			'user_id' => $this->userId,
			'date' => $this->date,
			'cough' => $this->cough,
			'breathlessness' => $this->breathlessness,
			'phlegm' => $this->phlegm,
			'other_symptoms' => $this->otherSymptoms,
			'prn_medication_puffs' => $this->prnMedicationPuffs,
			'medication1' => $this->medication1,
			'dose1' => $this->dose1,
			'medication2' => $this->medication2,
			'dose2' => $this->dose2,
			'medication3' => $this->medication3,
			'dose3' => $this->dose3
		]);

		$updatedMeasurement->setOtherSymptoms("Updated");

		$result = $this->controller
			->update(
				$id,
				$this->date,
				$this->cough,
				$this->breathlessness,
				$this->phlegm,
				"Updated",
				$this->prnMedicationPuffs,
				$this->medication1,
				$this->dose1,
				$this->medication2,
				$this->dose2,
				$this->medication3,
				$this->dose3);

		$this->assertEquals($updatedMeasurement, $result->getData());

		$this->measurementMapper->delete($result->getData());
	}

	public function testDelete() {
		$measurement = new Measurement();
		$measurement->setDate($this->date);
		$measurement->setUserId($this->userId);
		$measurement->setCough(0);
		$measurement->setBreathlessness(0);
		$measurement->setPhlegm(0);
		$measurement->setOtherSymptoms("non");
		$measurement->setPrnMedicationPuffs(0);
		$measurement->setMedication1("");
		$measurement->setDose1("");
		$measurement->setMedication2("");
		$measurement->setDose2("");
		$measurement->setMedication3("");
		$measurement->setDose3("");

		$insertedMeasurement = $this->measurementMapper->insert($measurement);
		$id = $insertedMeasurement->getId();

		$this->measurementMapper->delete($insertedMeasurement);

		$this->expectException(DoesNotExistException::class);
		$this->measurementMapper->find($id, $this->userId);
	}

	/**
	 * Deleting a measurement should also delete all values with its date
	 */
	public function testDeleteWithValue() {
		$measurement = new Measurement();
		$measurement->setDate($this->date);
		$measurement->setUserId($this->userId);
		$measurement->setCough(0);
		$measurement->setBreathlessness(0);
		$measurement->setPhlegm(0);
		$measurement->setOtherSymptoms("non");
		$measurement->setPrnMedicationPuffs(0);
		$measurement->setMedication1("");
		$measurement->setDose1("");
		$measurement->setMedication2("");
		$measurement->setDose2("");
		$measurement->setMedication3("");
		$measurement->setDose3("");

		$value = new Value();
		$value->setUserId($this->userId);
		$value->setDate($this->date);
		$value->setTime("22:00:00");
		$value->setValue("500");

		$insertedMeasurement = $this->measurementMapper->insert($measurement);
		$valueId = $this->valueMapper->insert($value)->getId();

		$this->measurementMapper->delete($insertedMeasurement);

		$this->expectException(DoesNotExistException::class);
		$this->valueMapper->find($valueId, $this->userId);
	}

	public function testCreateExisting() {
        $measurement = new Measurement();
        $measurement->setDate($this->date);
        $measurement->setUserId($this->userId);
        $measurement->setCough(0);
        $measurement->setBreathlessness(0);
        $measurement->setPhlegm(0);
        $measurement->setOtherSymptoms("non");
        $measurement->setPrnMedicationPuffs(0);
        $measurement->setMedication1("");
        $measurement->setDose1("");
        $measurement->setMedication2("");
        $measurement->setDose2("");
        $measurement->setMedication3("");
        $measurement->setDose3("");

        $this->measurementMapper->insert($measurement);
        $this->measurementMapper->insert($measurement);

        $this->expectException(ParameterValidationException::class);

    }

}
