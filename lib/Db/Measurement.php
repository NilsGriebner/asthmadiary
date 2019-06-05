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

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Measurement extends Entity implements JsonSerializable {

	protected $userId;
	protected $date;
	protected $cough;
	protected $breathlessness;
	protected $phlegm;
	protected $otherSymptoms;
	protected $prnMedicationPuffs;
	protected $medication1;
	protected $dose1;
	protected $medication2;
	protected $dose2;
	protected $medication3;
	protected $dose3;

	public function jsonSerialize() {
		return [
			'id' => $this->id,
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
		];
	}
}
