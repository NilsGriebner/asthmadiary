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
import VeeValidate, {Validator} from 'vee-validate';
import {generateFilePath} from 'nextcloud-server/dist/router';

import Vue from 'vue';
import router from './router';
import store from './store';
import App from './App';

import {validationFields} from './services/validation';

// CSP config
// eslint-disable-next-line
__webpack_nonce__ = btoa(OC.requestToken);
__webpack_public_path__ = generateFilePath('asthmadiary', '', 'js/')

//Validator config
Validator.localize('en', validationFields);

//Enable vue plugins
Vue.use(VeeValidate);

//Enable global functions t and n for vue
Vue.prototype.t = t;
Vue.prototype.n = n;

new Vue({
	el: '#content',
	name: 'AsthmaDiaryApp',
	router,
	store,
	render: h => h(App),
});
