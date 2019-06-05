/*
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

export default class Measurement {

	/**
	 * Needs an object as paramter with attributes:
	 * - id
	 * - userId
	 * - date
	 * - cough
	 * - breathlessness
	 * - phlegm
	 * - medication1
	 * - medication2
	 * - medication3
	 * - dose1
	 * - dose2
	 * - dose3
	 * - otherSymptoms
	 * - prnMedicationPuffs
	 * - values
	 */
	constructor (paramObject) {
		this.id = paramObject.id;
		this.user_id = paramObject.user_id;
		this.date = paramObject.date;
		this.cough = paramObject.cough;
		this.breathlessness = paramObject.breathlessness;
		this.phlegm = paramObject.phlegm;
		this.medication1 = paramObject.medication1;
		this.medication2 = paramObject.medication2;
		this.medication3 = paramObject.medication3;
		this.dose1 = paramObject.dose1;
		this.dose2 = paramObject.dose2;
		this.dose3 = paramObject.dose3;
		this.other_symptoms = paramObject.other_symptoms;
		this.prn_medication_puffs = paramObject.prn_medication_puffs;
		this.values = paramObject.values;
	}
}
