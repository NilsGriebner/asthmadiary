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
	<div id="asthmadiray_measurement_form_symptoms_wrapper">
		<div class="asthmadiary_flex_headline">
			<div class="icon-quota"></div>
			<h3> {{ t('asthmadiary', 'Symptoms') }}:</h3>
		</div>
		<div class="asthmadiary_flex_row">
			<div class="asthmadiary_flex_column">
				<label for="asthmadiary_cough_input">
					{{ t('asthmadiary', 'Cough') }}:
				</label>
				<select name="cough"
						id="asthmadiary_cough_input"
						:disabled="mode === select"
						:value="activeMeasurement.cough"
						@input="updateCough">
					<option value="0" :selected="cough === 0 || mode === add">
						&#128522; {{ t('asthmadiary', 'No') }}
					</option>
					<option value="1" :selected="cough === 1">
						&#128532; {{ t('asthmadiary', 'Low') }}
					</option>
					<option value="2" :selected="cough === 2">
						&#128542; {{ t('asthmadiary', 'Medium') }}
					</option>
					<option value="3" :selected="cough === 3">
						&#128557; {{ t('asthmadiary', 'High') }}
					</option>
				</select>
			</div>
			<div class="asthmadiary_flex_column">
				<label for="asthmadiary_breathlessness_input">
					{{ t('asthmadiary', 'Breathlessness') }}:
				</label>
				<select name="breathlessness"
						id="asthmadiary_breathlessness_input"
						:value="activeMeasurement.breathlessness"
						:disabled="mode === select"
						@input="updateBreathlessness">
					<option value="0"
							:selected="breathlessness === 0 || mode === add">
						&#128522; {{ t('asthmadiary', 'No') }}
					</option>
					<option value="1" :selected="breathlessness === 1">
						&#128532; {{ t('asthmadiary', 'Low') }}
					</option>
					<option value="2" :selected="breathlessness === 2">
						&#128542; {{ t('asthmadiary', 'Medium') }}
					</option>
					<option value="3" :selected="breathlessness === 3">
						&#128557; {{ t('asthmadiary', 'High') }}
					</option>
				</select>
			</div>
			<div class="asthmadiary_flex_column">
				<label for="asthmadiary_phlegm_input">
					{{ t('asthmadiary', 'Phlegm') }}:
				</label>
				<select name="phlegm"
						id="asthmadiary_phlegm_input"
						:value="activeMeasurement.phlegm"
						:disabled="mode === select"
						@input="updatePhlegm">
					<option value="0" :selected="phlegm === 0 || mode === add">
						&#128522; {{ t('asthmadiary', 'No') }}
					</option>
					<option value="1" :selected="phlegm === 1">
						&#128532; {{ t('asthmadiary', 'Low') }}
					</option>
					<option value="2" :selected="phlegm === 2">
						&#128542; {{ t('asthmadiary', 'Medium') }}
					</option>
					<option value="3" :selected="phlegm === 3">
						&#128557; {{ t('asthmadiary', 'High') }}
					</option>
				</select>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex';

	import {VIEW_MODES} from "../../services/utils";

	export default {

		name: "MeasurementsContentSymptomsSection",

		computed: {
			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
				mode: state => state.measurements.mode,
				breathlessness: state => state.measurements.activeMeasurement.breathlessness,
				cough: state => state.measurements.activeMeasurement.cough,
				phlegm: state => state.measurements.activeMeasurement.phlegm,
			}),

			select () {
				return VIEW_MODES.SELECT;
			},

			add () {
				return VIEW_MODES.ADD;
			},

			edit () {
				return VIEW_MODES.EDIT;
			},
		},

		methods: {
			updateBreathlessness (value) {
				this.$store.dispatch('updateBreathlessness', value);
			},

			updateCough (value) {
				this.$store.dispatch('updateCough', value);
			},

			updatePhlegm (value) {
				this.$store.dispatch('updatePhlegm', value);
			},

			validateInput () {
				return this.$validator.validate();
			},
		},
	}
</script>

<style></style>