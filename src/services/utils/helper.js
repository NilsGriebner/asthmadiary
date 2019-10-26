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

import {API_DATE_FORMAT} from './Strings';
import {MEASUREMENTS_URL} from './Strings';
import {VALUE_URL} from './Strings';

import Measurement from '../../Models/measurement';
import axios from "axios";

export function createMeasurementDummy (day) {
	let paramObject = {};
	paramObject.id = null;
	paramObject.user_id = null;
	paramObject.date = day.format(API_DATE_FORMAT);
	paramObject.cough = 0;
	paramObject.breathlessness = 0;
	paramObject.phlegm = 0;
	paramObject.medication1 = null;
	paramObject.medication2 = null;
	paramObject.medication3 = null;
	paramObject.dose1 = null;
	paramObject.dose2 = null;
	paramObject.dose3 = null;
	paramObject.other_symptoms = null;
	paramObject.prn_medication_puffs = 0;
	paramObject.values = [];

	return new Measurement(paramObject);
}

export function createNewMeasurement (context, measurement) {
	if (context.state.values.length !== 0) {
		measurement.values = context.state.values.filter(v => v.date === measurement.date);

	} else {
		measurement.values = [];
	}

	return new Measurement(measurement);
}

export function getMeasurementByDate (date) {
	return axios.get(MEASUREMENTS_URL, {
		params: {
			'from': date,
			'to': date
		}
	});
}

export function calculateFrom (date) {
	return date.startOf('month').format(API_DATE_FORMAT);
}

export function calculateTo (date) {
	return date.endOf('month').format(API_DATE_FORMAT);
}

export function getDaysOfMonth (date) {
	let daysInMonth = date.daysInMonth();
	let arrDays = [];

	while (daysInMonth) {
		let current = moment().month(date.month()).date(daysInMonth);
		arrDays.push(current);
		daysInMonth--;
	}

	return arrDays.reverse();
}

export function getActiveMeasurementUrl (measurment) {
	return MEASUREMENTS_URL + '/' + measurment.id;
}

export function getValueUrl (value) {
	return VALUE_URL + '/' + value.id;
}

export function getMeasurementPositionByDate (measurements, date) {
	return measurements.map(i => i.date).indexOf(date);
}

export function calculateBackgroundColor (string) {
	try {
		let color = string.toRgb();
		return `rgb(${color.r}, ${color.g}, ${color.b})`;
	} catch (e) {
		return `rgb(141, 197, 156)`;
	}
}