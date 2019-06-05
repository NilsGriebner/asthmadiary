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
import {MEASUREMENT_LIST_NA_VALUE} from "../services/utils";

export default {

	getValues: (state) => {
		return state.measurements.values;
	},

	getActiveMeasurement: (state) => {
		return state.activeMeasurement;
	},

	getBestValueOfMeasurement: (state) => (date) => {
		let measurement = state.measurements.filter(m => m.date === date)[0];
		if (measurement.values === null) {
			return MEASUREMENT_LIST_NA_VALUE;
		} else {
			if (measurement.values.length === 0) {
				return MEASUREMENT_LIST_NA_VALUE;
			}
			return measurement.values
				.map(v => v.value)
				.sort()[measurement.values.length - 1];
		}
	},

	getValueForStatusBar: (state) => (date) => {
		let measurement = state.measurements.filter(m => m.date === date)[0];
		switch (parseInt(measurement.breathlessness)) {
			case 0:
				return 0;
			case 1:
				return 25;
			case 2:
				return 75;
			case 3:
				return 100;
			default:
				return 0;
		}
	},

	getMedicationPuffsForStatusBar: (state) => (date) => {
		let measurement = state.measurements.filter(m => m.date === date)[0];
		if (measurement.prn_medication_puffs === null) {
			return 0;
		}
		return measurement.prn_medication_puffs;
	},

	getBreathlessnessValues: (state) => {
		let result = [];
		for (let measurement of state.measurements) {
			result.push(parseInt(measurement.breathlessness));
		}
		return result;
	},

	getCoughValues: (state) => {
		let result = [];
		for (let measurement of state.measurements) {
			result.push(parseInt(measurement.cough));
		}
		return result;
	},

	getPhlegmValues: (state) => {
		let result = [];
		for (let measurement of state.measurements) {
			result.push(parseInt(measurement.phlegm));
		}
		return result;
	},

	getDateFormat: (state) => {
		return state.measurements.dateFormat;
	}
};
