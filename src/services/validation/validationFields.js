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
import {MEASUREMENT_INPUT_ALLOWED_CHARS} from '../utils';
import {MEASUREMENT_INPUT_REQUIRED} from '../utils';
import {MEASUREMENT_INPUT_ALLOWED_NUM} from '../utils';
import {MEASUREMENT_VALUE_TIME_FORMAT_ERROR} from '../utils';
import {MEASUREMENT_VALUE_INPUT_ERROR} from '../utils';
import {MEASUREMENT_VALUE_INPUT_REQUIRED_ERROR} from '../utils';
import {MEASUREMENT_VALUE_INPUT_LENGTH_ERROR} from '../utils';

export const validationFields = {
	custom: {
		medication1: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS,
			required: MEASUREMENT_INPUT_REQUIRED,
			max: MEASUREMENT_VALUE_INPUT_LENGTH_ERROR
		},
		dose1: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS,
			required: MEASUREMENT_INPUT_REQUIRED,
			max: MEASUREMENT_VALUE_INPUT_LENGTH_ERROR
		},
		medication2: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS,
			required: MEASUREMENT_INPUT_REQUIRED,
			max: MEASUREMENT_VALUE_INPUT_LENGTH_ERROR
		},
		dose2: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS,
			required: MEASUREMENT_INPUT_REQUIRED,
			max: MEASUREMENT_VALUE_INPUT_LENGTH_ERROR
		},
		medication3: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS,
			required: MEASUREMENT_INPUT_REQUIRED,
			max: MEASUREMENT_VALUE_INPUT_LENGTH_ERROR
		},
		dose3: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS,
			required: MEASUREMENT_INPUT_REQUIRED,
			max: MEASUREMENT_VALUE_INPUT_LENGTH_ERROR
		},
		prn_medication_puffs: {
			regex: MEASUREMENT_INPUT_ALLOWED_NUM
		},
		other_symptoms: {
			regex: MEASUREMENT_INPUT_ALLOWED_CHARS
		},
		value: {
			min_value: MEASUREMENT_VALUE_INPUT_ERROR,
			max_value: MEASUREMENT_VALUE_INPUT_ERROR,
			required: MEASUREMENT_VALUE_INPUT_REQUIRED_ERROR
		},
		time: {
			date_format: MEASUREMENT_VALUE_TIME_FORMAT_ERROR,
			required: MEASUREMENT_VALUE_INPUT_REQUIRED_ERROR
		}
	}
};
