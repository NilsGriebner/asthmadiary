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
		<div class="asthmadiary_flex_headline">
			<div class="icon-category-installed"></div>
			<h3> {{ t('asthmadiary', 'Medication') }}: </h3>
		</div>
		<div class="asthmadiary_flex_row">
			<div class="asthmadiary_flex_column">
				<div class="asthmadiary_flex_column">
					<label for="asthmadiary_medication1_input">
						{{ t('asthmadiary', '1. Medication') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="medication1"
							   id="asthmadiary_medication1_input"
							   :placeholder="t('asthmadiary', '1. Medication')"
							   :readonly="mode === select"
							   :value="activeMeasurement.medication1"
							   v-validate="{ required: medication1Required, regex:/^[a-zA-Z0-9_ ]+$/, max: 100 }"
							   @input="updateMedication1"
						/>
						<a class="icon-delete"
							 v-on:click.prevent="updateMedication1(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('medication1') }}
					</span>
				</div>
				<div class="asthmadiary_flex_column">
					<label for="asthmadiary_dose1_input">
						{{ t('asthmadiary', '1. Dose') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="dose1"
							   id="asthmadiary_dose1_input"
							   :placeholder="t('asthmadiary', '1.Dose')"
							   :readonly="mode === select"
							   :value="activeMeasurement.dose1"
							   v-validate="{ required: dose1Required, regex:/^[a-zA-Z0-9_ ]+$/, max: 100 }"
							   @input="updateDose1"
						/>
						<a class="icon-delete"
							 v-on:click.prevent="updateDose1(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('dose1') }}
					</span>
				</div>
			</div>
			<div class="asthmadiary_flex_column">
				<div class="asthmadiary_flex_column">
					<label for="asthmadiary_medication2_input">
						{{ t('asthmadiary', '2. Medication') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="medication2"
							   id="asthmadiary_medication2_input"
							   :placeholder="t('asthmadiary', '2. Medication')"
							   :readonly="mode === select"
							   :value="activeMeasurement.medication2"
							   v-validate="{ required: medication2Required, regex:/^[a-zA-Z0-9_ ]+$/, max: 100 }"
							   @input="updateMedication2"/>
						<a class="icon-delete"
							 v-on:click.prevent="updateMedication2(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('medication2') }}
					</span>
				</div>
				<div class="asthmadiary_flex_column">
					<label for="asthmadiary_dose2_input">
						{{ t('asthmadiary', '2. Dose') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="dose2"
							   id="asthmadiary_dose2_input"
							   :placeholder="t('asthmadiary', '2. Dose')"
							   :readonly="mode === select"
							   :value="activeMeasurement.dose2"
							   v-validate="{ required: dose2Required, regex:/^[a-zA-Z0-9_ ]+$/, max: 100 }"
							   @input="updateDose2"
						/>
						<a class="icon-delete"
							 v-on:click.prevent="updateDose2(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('dose2') }}
					</span>
				</div>
			</div>
			<div>
				<div class="asthmadiary_flex_column">
					<label for="asthmadiary_medication3_input">
						{{ t('asthmadiary', '3. Medication') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="medication3"
							   id="asthmadiary_medication3_input"
							   :placeholder="t('asthmadiary', '3. Medication')"
							   :readonly="mode === select"
							   :value="activeMeasurement.medication3"
							   v-validate="{ required: medication3Required, regex:/^[a-zA-Z0-9_ ]+$/, max: 100 }"
							   @input="updateMedication3"/>
						<a class="icon-delete"
							 v-on:click.prevent="updateMedication3(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('medication3') }}
					</span>
					<label for="asthmadiary_dose3_input">
						{{ t('asthmadiary', '3. Dose') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="dose3"
							   id="asthmadiary_dose3_input"
							   :placeholder="t('asthmadiary', '3. Dose')"
							   :readonly="mode === select"
							   :value="activeMeasurement.dose3"
							   v-validate="{ required: dose3Required, regex:/^[a-zA-Z0-9_ ]+$/, max: 100 }"
							   @input="updateDose3"
						/>
						<a class="icon-delete"
							 v-on:click.prevent="updateDose3(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('dose3') }}
					</span>
				</div>
			</div>
			<div>
				<div class="asthmadiary_flex_column">
					<label for="asthmadiary_prn_input">
						{{ t('asthmadiary',
						'PRN Medication Dose') }}:
					</label>
					<div class="asthmadiary_flex_row_persistent">
						<input name="prn_medication_puffs"
							   id="asthmadiary_prn_input"
							   :placeholder="t('asthmadiary', 'PRN Medication Dose')"
							   :readonly="mode === select"
							   :value="activeMeasurement.prn_medication_puffs"
							   v-validate="{ regex:/^[0-9]+$/, max_value: 100, min_value: 0 }"
							   @input="updatePrnMedicationPuffs"
						/>
						<a class="icon-delete"
							 v-on:click.prevent="updatePrnMedicationPuffs(null)">
						</a>
					</div>
					<span class="asthmadiary_error_message_text">
						{{ errors.first('prn_medication_puffs') }}
					</span>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import {mapState} from 'vuex';

	import {VIEW_MODES} from "../../services/utils";

	export default {

		name: "MeasurementsContentMedicationSection",

		computed: {
			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
				mode: state => state.measurements.mode,
				medication1: state => state.measurements.activeMeasurement.medication1,
				medication2: state => state.measurements.activeMeasurement.medication2,
				medication3: state => state.measurements.activeMeasurement.medication3,
				dose1: state => state.measurements.activeMeasurement.dose1,
				dose2: state => state.measurements.activeMeasurement.dose2,
				dose3: state => state.measurements.activeMeasurement.dose3,
				prnMedicationPuffs: state => state.measurements.activeMeasurement.prnMedicationPuffs,
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

			medication1Required () {
				if (this.medication1 === null && this.dose1 === null) {
					return false;
				}

				return this.dose1 !== null;
			},

			medication2Required () {
				if (this.medication2 === null && this.dose2 === null) {
					return false;
				}
				return this.dose2 !== null;
			},

			medication3Required () {
				if (this.medication3 === null && this.dose3 === null) {
					return false;
				}
				return this.dose3 !== null;
			},

			dose1Required () {
				if (this.medication1 === null && this.dose1 === null) {
					return false;
				}
				return this.medication1 !== null;
			},

			dose2Required () {
				if (this.medication2 === null && this.dose2 === null) {
					return false;
				}
				return this.medication2 !== null;
			},

			dose3Required () {
				if (this.medication3 === null && this.dose3 === null) {
					return false;
				}
				return this.medication3 !== null;
			},
		},

		watch: {
			medication1 () {
				if (this.medication1 === "") {
					this.updateMedication1(null);
				}
			},

			dose1 () {
				if (this.dose1 === "") {
					this.updateDose1(null);
				}
			},

			medication2 () {
				if (this.medication2 === "") {
					this.updateMedication2(null);
				}
			},

			dose2 () {
				if (this.dose2 === "") {
					this.updateDose2(null);
				}
			},

			medication3 () {
				if (this.medication3 === "") {
					this.updateMedication3(null);
				}
			},

			dose3 () {
				if (this.dose3 === "") {
					this.updateDose3(null);
				}
			},

			prnMedicationPuffs () {
				if (this.prnMedicationPuffs === "") {
					this.updatePrnMedicationPuffs(null);
				}
			},
		},

		methods: {
			updateMedication1 (value) {
				this.$store.dispatch('updateMedication1', value);
			},

			updateMedication2 (value) {
				this.$store.dispatch('updateMedication2', value);
			},

			updateMedication3 (value) {
				this.$store.dispatch('updateMedication3', value);
			},

			updateDose1 (value) {
				this.$store.dispatch('updateDose1', value);
			},

			updateDose2 (value) {
				this.$store.dispatch('updateDose2', value);
			},

			updateDose3 (value) {
				this.$store.dispatch('updateDose3', value);
			},

			updatePrnMedicationPuffs (value) {
				this.$store.dispatch('updatePrnMedicationPuffs', value);
			},

			validateInput () {
				return this.$validator.validate();
			},
		}
	}
</script>

<style></style>