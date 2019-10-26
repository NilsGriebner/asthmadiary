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

/**
 * Strings for global use
 */
export const BASE_URL = OC.generateUrl('/apps/asthmadiary');
export const MEASUREMENTS_URL = BASE_URL + '/api/measurements';
export const VALUE_URL = BASE_URL + '/api/values';
export const SERVER_FETCH_ERROR_MESSAGE =
	t('asthmadiary', 'Unable to fetch data from server');
export const SERVER_DELETE_ERROR_MESSAGE =
	t('asthmadiary', 'Unable to delete data from server');
export const SERVER_SUBMIT_ERROR_MESSAGE =
	t('asthmadiary', 'Unable to submit data');
export const API_DATE_FORMAT = 'YYYY-MM-DD';
export const SHOW_DATE_FORMAT_DE = 'D. MMMM YYYY';
export const NAVIGATION_DATE_FORMAT = 'MMMM YYYY';
export const MEASUREMENT_LIST_NA_VALUE = "NA";
export const MEASUREMENT_INPUT_REQUIRED =
	t('asthmadiary', 'For every medication a dose is required!');
export const MEASUREMENT_INPUT_ALLOWED_CHARS =
	t('asthmadiary', "Allowed characters: A-Z, a-z, 0-9");
export const MEASUREMENT_INPUT_WRONG_SELECT =
	t('asthmadiary', "You provided a wrong value for Cough, Breathlessness or Phlegm!");
export const MEASUREMENT_INPUT_ALLOWED_NUM =
	t('asthmadiary', "Allowed characters: 0-9");
export const MEASUREMENT_INPUT_CORRECT_FORM =
	t('asthmadiary', "Please correct your input first!");
export const MEASUREMENT_VALUE_TIME_FORMAT_ERROR =
	t('asthmadiary', "Time format") + " HH:MM:SS";
export const MEASUREMENT_VALUE_INPUT_ERROR =
	t('asthmadiary', "Provide a number between 1 and 900");
export const MEASUREMENT_VALUE_INPUT_REQUIRED_ERROR =
	t('asthmadiary', "Value and time are required");
export const MEASUREMENT_VALUE_INPUT_DOUBLE_ERROR =
	t('asthmadiary', "A value with this time already exist!");
export const MEASUREMENT_VALUE_INPUT_LENGTH_ERROR =
	t('asthmadiary', "You exceeded the max length of 100 characters");
export const MEASUREMENT_VALUE_SUBMIT_MEASUREMENT_FIRST_ERROR =
	t('asthmadiary', "You need to save your measurement first");
export const MEASUREMENT_NO_PREV_MEDICATION =
	t('asthmadiary', "No medication available for previous day");
