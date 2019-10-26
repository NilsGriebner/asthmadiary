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

import {VIEW_MODES} from './ViewModes';
import {
	SERVER_FETCH_ERROR_MESSAGE,
	SERVER_DELETE_ERROR_MESSAGE,
	SERVER_SUBMIT_ERROR_MESSAGE,
	API_DATE_FORMAT,
	SHOW_DATE_FORMAT_DE,
	MEASUREMENT_LIST_NA_VALUE,
	MEASUREMENT_INPUT_ALLOWED_CHARS,
	MEASUREMENT_INPUT_WRONG_SELECT,
	MEASUREMENT_INPUT_ALLOWED_NUM,
	MEASUREMENT_INPUT_REQUIRED,
	MEASUREMENT_INPUT_CORRECT_FORM,
	MEASUREMENT_VALUE_TIME_FORMAT_ERROR,
	MEASUREMENT_VALUE_INPUT_ERROR,
	MEASUREMENT_VALUE_INPUT_REQUIRED_ERROR,
	MEASUREMENT_VALUE_INPUT_DOUBLE_ERROR,
	MEASUREMENT_VALUE_INPUT_LENGTH_ERROR,
	MEASUREMENT_VALUE_SUBMIT_MEASUREMENT_FIRST_ERROR,
	NAVIGATION_DATE_FORMAT,
	BASE_URL,
	MEASUREMENTS_URL,
	VALUE_URL,
	MEASUREMENT_NO_PREV_MEDICATION
} from './Strings';
import {
	createMeasurementDummy,
	createNewMeasurement,
	calculateFrom,
	calculateTo,
	getDaysOfMonth,
	getActiveMeasurementUrl,
	getValueUrl,
	getMeasurementPositionByDate,
	calculateBackgroundColor,
	getMeasurementByDate,
} from './helper';


export {VIEW_MODES};
export {SERVER_FETCH_ERROR_MESSAGE};
export {SERVER_DELETE_ERROR_MESSAGE};
export {SERVER_SUBMIT_ERROR_MESSAGE};
export {API_DATE_FORMAT};
export {SHOW_DATE_FORMAT_DE};
export {NAVIGATION_DATE_FORMAT};
export {MEASUREMENT_LIST_NA_VALUE};
export {MEASUREMENT_INPUT_ALLOWED_CHARS};
export {MEASUREMENT_INPUT_WRONG_SELECT};
export {MEASUREMENT_INPUT_ALLOWED_NUM};
export {MEASUREMENT_INPUT_REQUIRED};
export {MEASUREMENT_INPUT_CORRECT_FORM};
export {MEASUREMENT_VALUE_INPUT_ERROR};
export {MEASUREMENT_VALUE_TIME_FORMAT_ERROR};
export {MEASUREMENT_VALUE_INPUT_REQUIRED_ERROR};
export {MEASUREMENT_VALUE_INPUT_DOUBLE_ERROR};
export {MEASUREMENT_VALUE_INPUT_LENGTH_ERROR};
export {MEASUREMENT_VALUE_SUBMIT_MEASUREMENT_FIRST_ERROR};
export {MEASUREMENT_NO_PREV_MEDICATION};
export {createMeasurementDummy};
export {createNewMeasurement};
export {calculateFrom};
export {calculateTo};
export {getDaysOfMonth};
export {getActiveMeasurementUrl};
export {getValueUrl};
export {getMeasurementPositionByDate};
export {calculateBackgroundColor};
export {getMeasurementByDate};
export {BASE_URL};
export {MEASUREMENTS_URL};
export {VALUE_URL};
