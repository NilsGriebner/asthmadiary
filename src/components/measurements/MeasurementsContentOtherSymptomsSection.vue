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
	<div id="asthmadiray_measurement_form_other_symptoms_wrapper">
		<div class="asthmadiary_flex_headline">
			<div class="icon-rename"></div>
			<h3>{{ t('asthmadiary', 'Other Symptoms and Notes') }}:</h3>
		</div>
		<div class="asthmadiary_flex_row">
		<textarea name="other_symptoms"
				  id="asthmadiary_measurement_form_other_symptoms_input"
				  :placeholder="t('asthmadiary','Write down other symtpoms or notes')"
				  :value="otherSymptoms"
				  :disabled="mode === select"
				  v-validate="{ regex:/^[a-zA-Z0-9_ .,]+$/, max: 100 }"
				  @input="updateOtherSymptoms">
		</textarea>
		</div>
		<span class="asthmadiary_error_message_text">
			{{ errors.first('other_symptoms') }}
		</span>
	</div>
</template>

<script>
	import {mapState} from 'vuex';
	import {VIEW_MODES} from "../../services/utils";

	export default {

		name: "MeasurementsContentOtherSymptomsSection",

		computed: {
			...mapState({
				mode: state => state.measurements.mode,
				otherSymptoms: state => state.measurements.activeMeasurement.other_symptoms,
			}),

			select () {
				return VIEW_MODES.SELECT;
			},
		},

		watch: {
			otherSymptoms () {
				if (this.otherSymptoms === "") {
					this.updateOtherSymptoms(null);
				}
			},

		},

		methods: {
			updateOtherSymptoms (value) {
				this.$store.dispatch('updateOtherSymptoms', value);
			},

			validateInput () {
				return this.$validator.validate();
			},
		},
	}
</script>

<style></style>