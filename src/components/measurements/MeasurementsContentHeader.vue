<!--
  - /**
  -  *
  -  * @copyright Copyright (c) 2019, Nils Griebner (nils@nils-griebner.de)
  -  *
  -  * @license GNU AGPL version 3 or any later version
  -  *
  -  * This program is free software: you can redistribute it and/or modify
  -  * it under the terms of the GNU Affero General Public License as
  -  * published by the Free Software Foundation, either version 3 of the
  -  * License, or (at your option) any later version.
  -  *
  -  * This program is distributed in the hope that it will be useful,
  -  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  -  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  -  * GNU Affero General Public License for more details.
  -  *
  -  * You should have received a copy of the GNU Affero General Public License
  -  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  -  *
  -  */
  -->

<template>
    <div v-if="!mobile"
         id="asthmadiary_measurement_content_header_full"
         :style="{ 'background-color': setColor(activeMeasurement.date) }">
        <div id="asthmadiary_measurement_content_header_full_text">
            <div>
				<span class="asthmadiary_header_first_line">
					{{ formatDate }}
				</span>
            </div>
            <div v-if="activeMeasurement.user_id !== null">
                {{ activeMeasurement.user_id }}
            </div>
        </div>
    </div>
</template>

<script>
	import {mapState} from 'vuex';

	import isMobile from 'nextcloud-vue/dist/Mixins/isMobile'

	import {calculateBackgroundColor} from '../../services/utils';
	import {API_DATE_FORMAT} from '../../services/utils';

	export default {

		name: "MeasurementsContentHeader",

		mixins: [
			isMobile
		],

        data () {
			return {
		        mobile: this._isMobile()
            }
        },

		computed: {
			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
				dateFormat: state => state.measurements.dateFormat,
			}),

			formatDate () {
				return moment(this.activeMeasurement.date, API_DATE_FORMAT)
					.format(this.dateFormat);
			},
		},

		methods: {
			setColor (string) {
				return calculateBackgroundColor(string);
			},
		},
	}
</script>

<style></style>