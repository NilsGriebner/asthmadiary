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
import Vue from 'vue';
import VueX, {Store} from 'vuex';

import actions from './actions';
import mutations from './mutations';
import getters from './getters';
import {VIEW_MODES} from "../services/utils";

Vue.use(VueX);


export default new Store({
	modules: {
		measurements: {
			state: {
				measurements: [],
				values: [],
				activeMeasurement: null,
				date: moment(),
				mode: VIEW_MODES.SELECT,
				dateFormat: null,
				currentLocale: 'en'
			},
			getters,
			mutations,
			actions
		}
	},
});

