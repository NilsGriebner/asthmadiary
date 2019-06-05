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
import axios from "axios";
import {
	API_DATE_FORMAT,
	calculateFrom,
	calculateTo,
	createMeasurementDummy,
	createNewMeasurement,
	getActiveMeasurementUrl,
	getDaysOfMonth,
	getMeasurementPositionByDate, getValueUrl,
	MEASUREMENTS_URL,
	SERVER_DELETE_ERROR_MESSAGE,
	SERVER_FETCH_ERROR_MESSAGE,
	SERVER_SUBMIT_ERROR_MESSAGE,
	SHOW_DATE_FORMAT_DE, VALUE_URL,
	VIEW_MODES
} from "../services/utils";
import Value from "../Models/value";

export default {

	/**
	 * Get user language from api and set date format
	 *
	 * @param context
	 * @returns {AxiosPromise<any>}
	 */
	setInitialDateFormat (context) {
		const URL = '/ocs/v2.php/cloud/user';
		return axios.get(URL, {
			headers: {
				"OCS-APIRequest": true
			}
		})
			.then(response => {
				let locale = response.data.ocs.data.language;
				context.commit('updateCurrentLocale', locale);
				if (context.state.currentLocale === "de") {
					context.commit('updateDateFormat', SHOW_DATE_FORMAT_DE);
				} else {
					context.commit('updateDateFormat', API_DATE_FORMAT);
				}
			});
	},

	/**
	 * Update date format
	 *
	 * @param context
	 * @param dateFormat
	 * @returns {Promise<any>}
	 */
	updateDateFormat (context, dateFormat) {
		return new Promise(resolve => {
			context.commit('updateDateFormat', dateFormat);
			resolve();
		});
	},

	/**
	 * Add month to current date
	 *
	 * @param context
	 */
	addMonth (context) {
		return new Promise(resolve => {
			context.commit('addMonth');
			context.dispatch('getValues').then(() => {
				context.dispatch('getMeasurements').then(() => {
					context.dispatch('setFirstMeasurementActive')
						.then(() => {
							resolve();
						});

				});
			});
		});
	},

	/**
	 * Subtract month
	 *
	 * @param context
	 */
	subtractMonth (context) {
		return new Promise(resolve => {
			context.commit('subtractMonth');
			context.dispatch('getValues').then(() => {
				context.dispatch('getMeasurements').then(() => {
					context.dispatch('setFirstMeasurementActive')
						.then(() => {
							resolve();
						});
				});
			});
		});
	},

	/**
	 * Retrieve and commit measurements
	 *
	 * @param context
	 */
	getMeasurements (context) {
		return axios.get(MEASUREMENTS_URL, {
			params: {
				'from': calculateFrom(context.state.date),
				'to': calculateTo(context.state.date)
			}
		})
			.then(function (getResponse) {
				if (getResponse.data.length === 0) {
					/**
					 * We dont get measurements from api. Create dummies
					 * from moment time range
					 */
					const measurements = getDaysOfMonth(context.state.date)
						.map(day => {
							return createMeasurementDummy(day);
						});

					context.commit('addMeasurements', measurements);

				} else {
					/**
					 * Got measurements from api. Merge them with dummies
					 */
					const measurements = getDaysOfMonth(context.state.date)
						.map(day => {
							const filteredResponse =
								getResponse
									.data
									.filter(
										m => m.date === day.format(API_DATE_FORMAT));
							if (filteredResponse.length === 1) {
								return createNewMeasurement(
									context, filteredResponse[0]);
							} else {
								return createMeasurementDummy(day);
							}
						});

					context.commit('addMeasurements', measurements);
				}
			})
			.catch(function (error) {
				console.error(error);
				alert(SERVER_FETCH_ERROR_MESSAGE);
			});
	},

	/**
	 * Delete active measurement
	 *
	 * @param context
	 * @param measurement
	 */
	deleteMeasurement (context, measurement) {
		const URL = getActiveMeasurementUrl(measurement);

		return axios.delete(URL).then(() => {
			context.dispatch('getValues').then(() => {
				context.dispatch('getMeasurements').then(() => {
					context.dispatch('setActiveMeasurement', measurement)
						.then(() => {
							context.dispatch('updateMode', VIEW_MODES.SELECT);
						});
				});
			});
		}).catch(error => {
			console.error(error);
			alert(SERVER_DELETE_ERROR_MESSAGE);
		});
	},

	/**
	 * Set measurement active
	 *
	 * @param context
	 * @param measurement
	 */
	setActiveMeasurement (context, measurement) {
		let pos = getMeasurementPositionByDate(
			context.state.measurements, measurement.date);

		return new Promise(resolve => {
			context.commit('setActiveMeasurement', pos);
			resolve();
		});
	},

	/**
	 * Set first measurement active
	 *
	 * @param context
	 */
	setFirstMeasurementActive (context) {
		return new Promise(resolve => {
			context.commit('setFirstMeasurementActive');
			resolve();
		});
	},

	/**
	 * Add measurements
	 *
	 * @param context
	 * @param measurements
	 */
	addMeasurements (context, measurements) {
		return new Promise(resolve => {
			context.commit('addMeasurements', measurements);
			resolve();
		});
	},

	/**
	 * Add measurement
	 *
	 * @param context
	 */
	addMeasurement (context) {

		let submitObject = {};

		/**
		 * Dont sumbit these keys to server
		 */
		let dontSubmitKeys = ['values', 'user_id', 'id'];

		for (let key in context.state.activeMeasurement) {

			if (context.state.activeMeasurement[key] !== null &&
				dontSubmitKeys.indexOf(key) === -1) {
				submitObject[key] = context.state.activeMeasurement[key];
			}
		}

		if (context.state.mode === VIEW_MODES.EDIT) {

			const URL = getActiveMeasurementUrl(context.state.activeMeasurement);

			return axios.put(URL, submitObject).then(() => {
				context.dispatch('getValues').then(() => {
					context.dispatch('getMeasurements').then(() => {
						context.dispatch('setActiveMeasurement', submitObject)
							.then(() => {
								context.dispatch('updateMode', VIEW_MODES.SELECT);
							});
					});
				});
			})
				.catch(error => {
					console.error(error);
					alert(SERVER_SUBMIT_ERROR_MESSAGE);
				});
		} else {
			return axios.post(MEASUREMENTS_URL, submitObject).then(() => {
				context.dispatch('getValues').then(() => {
					context.dispatch('getMeasurements').then(() => {
						context.dispatch('setActiveMeasurement', submitObject)
							.then(() => {
								context.dispatch('updateMode', VIEW_MODES.SELECT);
							});
					});
				});
			})
				.catch(error => {
					console.error(error);
					alert(SERVER_SUBMIT_ERROR_MESSAGE);
				});
		}
	},

	/**
	 * Get values
	 *
	 * @param context
	 */
	getValues (context) {

		return axios.get(VALUE_URL, {
			params: {
				'from': calculateFrom(context.state.date),
				'to': calculateTo(context.state.date)
			}
		})
			.then(function (response) {
				if (response.data.length !== 0) {
					let values = [];
					for (let value of response.data) {
						let newValue = new Value(
							value.id, value.date, value.time, value.value, value.user_id);
						values.push(newValue);
					}
					context.commit('addValues', values);
				} else {
					context.commit('addValues', []);
				}
			})
			.catch(function (error) {
				console.error(error);
				alert(SERVER_FETCH_ERROR_MESSAGE);
			});
	},

	/**
	 *Delete value
	 *
	 * @param context
	 * @param value
	 */
	deleteValue (context, value) {

		const URL = getValueUrl(value);

		return axios.delete(URL)
			.then(function () {
				context.dispatch('getValues').then(() => {
					context.dispatch('getMeasurements')
						.then(() => {
							context.dispatch(
								'setActiveMeasurement',
								context.state.activeMeasurement);
						});
				});
			})
			.catch(function (error) {
				console.error(error);
				alert(SERVER_DELETE_ERROR_MESSAGE);
			});
	},
	/**
	 * Add value
	 *
	 * @param context
	 * @param value
	 */
	addValue (context, value) {
		return axios.post(VALUE_URL, value)
			.then(function () {
				context.dispatch('getValues')
					.then(() => {
						context.dispatch('getMeasurements').then(() => {
							context.dispatch(
								'setActiveMeasurement', context.state.activeMeasurement);
						});
					});
			})
			.catch(error => {
				console.error(error);
				alert(SERVER_SUBMIT_ERROR_MESSAGE);
			});
	},

	/**
	 * Update mode
	 *
	 * @param context
	 * @param mode
	 */
	updateMode (context, mode) {
		return new Promise(resolve => {
			context.commit('updateMode', mode);
			resolve();
		});
	},

	/**
	 * Update breathlessness value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateBreathlessness (context, value) {
		return new Promise(resolve => {
			context.commit('updateBreathlessness', value);
			resolve();
		});
	},

	/**
	 * Update cough value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateCough (context, value) {
		return new Promise(resolve => {
			context.commit('updateCough', value);
			resolve();
		});
	},

	/**
	 * Update phlegm value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updatePhlegm (context, value) {
		return new Promise(resolve => {
			context.commit('updatePhlegm', value);
			resolve();
		});
	},

	/**
	 * Update medication1 value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateMedication1 (context, value) {
		return new Promise(resolve => {
			context.commit('updateMedication1', value);
			resolve();
		});
	},

	/**
	 * Update medication2 value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateMedication2 (context, value) {
		return new Promise(resolve => {
			context.commit('updateMedication2', value);
			resolve();
		});
	},

	/**
	 * Update medication3 value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateMedication3 (context, value) {
		return new Promise(resolve => {
			context.commit('updateMedication3', value);
			resolve();
		});
	},

	/**
	 * Update dose1 value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateDose1 (context, value) {
		return new Promise(resolve => {
			context.commit('updateDose1', value);
			resolve();
		});
	},

	/**
	 * Update dose2 value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateDose2 (context, value) {
		return new Promise(resolve => {
			context.commit('updateDose2', value);
			resolve();
		});
	},

	/**
	 * Update dose3 value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateDose3 (context, value) {
		return new Promise(resolve => {
			context.commit('updateDose3', value);
			resolve();
		});
	},

	/**
	 * Update otherSymptoms value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updateOtherSymptoms (context, value) {
		return new Promise(resolve => {
			context.commit('updateOtherSymptoms', value);
			resolve();
		});
	},

	/**
	 * Update prnMedicationPuffs value of active measurement
	 *
	 * @param context
	 * @param value
	 */
	updatePrnMedicationPuffs (context, value) {
		return new Promise(resolve => {
			context.commit('updatePrnMedicationPuffs', value);
			resolve();
		});
	},
};
