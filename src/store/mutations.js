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
export default {

	addMeasurements (state, measurements) {
		state.measurements = measurements;
	},

	addMonth (state) {
		state.date = state.date.add(1, 'months');
	},

	subtractMonth (state) {
		state.date = state.date.subtract(1, 'months');
	},

	setFirstMeasurementActive (state) {
		state.activeMeasurement = state.measurements[0];
	},

	setActiveMeasurement (state, pos) {
		state.activeMeasurement = state.measurements[pos];
	},

	addValues (state, values) {
		state.values = values;
	},

	updateMode (state, mode) {
		state.mode = mode;
	},

	updateBreathlessness (state, e) {
		state.activeMeasurement.breathlessness = e.target.value;
	},

	updateCough (state, e) {
		state.activeMeasurement.cough = e.target.value;
	},

	updatePhlegm (state, e) {
		state.activeMeasurement.phlegm = e.target.value;
	},

	updateMedication1 (state, e) {
		if (e === null) {
			state.activeMeasurement.medication1 = null;
		} else if (e.target) {
			state.activeMeasurement.medication1 = e.target.value;
		} else {
			state.activeMeasurement.medication1 = e;
		}
	},

	updateMedication2 (state, e) {
		if (e === null) {
			state.activeMeasurement.medication2 = null;
		} else if (e.target) {
			state.activeMeasurement.medication2 = e.target.value;
		} else {
			state.activeMeasurement.medication2 = e;
		}
	},

	updateMedication3 (state, e) {
		if (e === null) {
			state.activeMeasurement.medication3 = null;
		} else if (e.target) {
			state.activeMeasurement.medication3 = e.target.value;
		} else {
			state.activeMeasurement.medication3 = e;
		}
	},

	updateDose1 (state, e) {
		if (e === null) {
			state.activeMeasurement.dose1 = null;
		} else if (e.target) {
			state.activeMeasurement.dose1 = e.target.value;
		} else {
			state.activeMeasurement.dose1 = e;
		}
	},

	updateDose2 (state, e) {
		if (e === null) {
			state.activeMeasurement.dose2 = null;
		} else if (e.target) {
			state.activeMeasurement.dose2 = e.target.value;
		} else {
			state.activeMeasurement.dose2 = e;
		}
	},

	updateDose3 (state, e) {
		if (e === null) {
			state.activeMeasurement.dose3 = null;
		} else if (e.target) {
			state.activeMeasurement.dose3 = e.target.value;
		} else {
			state.activeMeasurement.dose3 = e;
		}
	},

	updateOtherSymptoms (state, e) {
		if (e === null) {
			state.activeMeasurement.other_symptoms = null;
		} else {
			state.activeMeasurement.other_symptoms = e.target.value;
		}
	},

	updatePrnMedicationPuffs (state, e) {
		if (e === null) {
			state.activeMeasurement.prn_medication_puffs = null;
		} else if (e.target) {
			state.activeMeasurement.prn_medication_puffs = e.target.value;
		} else {
			state.activeMeasurement.prn_medication_puffs = e;
		}
	},

	updateDateFormat (state, e) {
		/**
		 * We get a string from initial action
		 */
		if (typeof (e) === "string") {
			state.dateFormat = e;
		} else {
			state.dateFormat = e.target.value;
		}
	},

	updateCurrentLocale (state, locale) {
		state.currentLocale = locale;
	}
};
