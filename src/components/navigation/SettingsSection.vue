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
	<div>
		<div class="asthmadiary_flex_column">
			<label for="asthmadiary_navigation_settings_date_format">
				{{ t('asthmadiary', "Date format") }}:
			</label>
			<select id="asthmadiary_navigation_settings_date_format"
					@input="updateDateFormat">
				<option :value="apiDateFormat"
						:selected="dateFormat === apiDateFormat"
				>
					{{ apiDateFormat }}
				</option>
				<option :value="showDateFormatDe"
						:selected="dateFormat === showDateFormatDe"
				>
					{{ showDateFormatDe }}
				</option>
			</select>
		</div>
	</div>

</template>

<script>
	import {API_DATE_FORMAT} from '../../services/utils';
	import {SHOW_DATE_FORMAT_DE} from '../../services/utils';

	import {mapState} from 'vuex';

	export default {

		name: "SettingsSection",

		computed: {
			...mapState({
				dateFormat: state => state.measurements.dateFormat,
			}),

			apiDateFormat () {
				return API_DATE_FORMAT;
			},

			showDateFormatDe () {
				return SHOW_DATE_FORMAT_DE;
			},
		},

		methods: {
			updateDateFormat (dateFormat) {
				this.$store.dispatch('updateDateFormat', dateFormat);
			}
		},
	}
</script>

<style></style>