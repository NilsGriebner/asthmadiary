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

namespace OCA\AsthmaDiary\Controller;

use OCA\AsthmaDiary\Service\MeasurementService;
use OCP\IRequest;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;

class MeasurementController extends Controller {

	private $measurementService;
	private $userId;

	use Errors;

	public function __construct(
		$AppName,
		IRequest $request,
		MeasurementService $measurementeService,
		$userId) {
		parent::__construct($AppName, $request);
		$this->measurementService = $measurementeService;
		$this->userId = $userId;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $from
	 * @param string $to
	 */
	public function index($from, $to) {
		return $this->handleNotFound(function () use ($from, $to) {
			return $this->measurementService
				->findAll($from, $to, $this->userId);
		});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $id
	 */
	public function show($id) {
		return $this->handleNotFound(function () use ($id) {
			return $this->measurementService->find($id, $this->userId);
		});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $date
	 * @param int $cough
	 * @param int $breathlessness
	 * @param int $phlegm
	 * @param string $other_symptoms
	 * @param int $prn_medication_puffs
	 * @param string $medication1
	 * @param string $dose1
	 * @param string $medication2
	 * @param string $dose2
	 * @param string $medication3
	 * @param string $dose3
	 */
	public function create($date,
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
		return $this->handleParameterValidation(function () use (
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
			$dose3
		) {
			return $this->measurementService->create($this->userId,
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
				$dose3);
		});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $id
	 * @param string $date
	 * @param int $cough
	 * @param int $breathlessness
	 * @param int $phlegm
	 * @param string $other_symptoms
	 * @param int $prn_medication_puffs
	 * @param string $medication1
	 * @param string $dose1
	 * @param string $medication2
	 * @param string $dose2
	 * @param string $medication3
	 * @param string $dose3
	 */
	public function update($id,
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

		return $this->handleParameteAndNotFoundException(function () use (
			$id,
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
			$dose3
		) {
			return $this->measurementService->update(
				$id,
				$this->userId,
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
				$dose3);
		});
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param int $id
	 */

	public function destroy($id) {
		return $this->handleNotFound(function () use ($id) {
			return $this->measurementService->delete($id, $this->userId);
		});
	}

}