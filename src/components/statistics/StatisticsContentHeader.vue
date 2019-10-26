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
         id="asthmadiary_statistics_header"
         :style="{'background-color': colorBackground()}">
        <div>
			<span class="asthmadiary_header_first_line">
				{{ t('asthmadiary', 'Monthly statistics for ') + headerMonth }}
			</span>
        </div>
    </div>
</template>

<script>
	import {calculateBackgroundColor} from '../../services/utils';

	import isMobile from 'nextcloud-vue/dist/Mixins/isMobile'

	import {mapState} from 'vuex';
	import moment from 'moment';

	export default {

		name: "StatisticsContentHeader",

        data () {
			return {
				mobile: isMobile
            }
        },

        mixins: {
			isMobile
        },

		computed: {
			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
				currentLocale: state => state.measurements.currentLocale,
			}),

			headerMonth () {
				let time = moment(this.activeMeasurement.date)
					.locale(this.currentLocale);
				return time.format('MMMM');
			}
		},

		methods: {
			colorBackground () {
				return calculateBackgroundColor(this.activeMeasurement.date);
			},
		}
	}
</script>
<style></style>
