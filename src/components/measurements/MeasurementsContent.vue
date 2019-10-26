<!--
  /**
   *
   * @copyright Copyright (c) 2019, Nils Griebner (nils@nils-griebner.de)
   *
   * @license GNU AGPL version 3 or any later version
   *
   * This program is free software: you can redistribute it and/or modify
   * it under the terms of the GNU Affero General Public License as
   * published by the Free Software Foundation, either version 3 of the
   * License, or (at your option) any later version.
   *
   * This program is distributed in the hope that it will be useful,
   * but WITHOUT ANY WARRANTY; without even the implied warranty of
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   * GNU Affero General Public License for more details.
   *
   * You should have received a copy of the GNU Affero General Public License
   * along with this program.  If not, see <http://www.gnu.org/licenses/>.
   *
   */
  -->

<script>
	import {mapState} from 'vuex';

	import MeasurementsContentValueList from './MeasurementsContentValueList';
	import MeasurementsContentHeader from './MeasurementsContentHeader';
	import MeasurementsContentMedicationSection
		from './MeasurementsContentMedicationSection';
	import MeasurementsContentSymptomsSection
		from './MeasurementsContentSymptomsSection';
	import MeasurementsContentOtherSymptomsSection
		from './MeasurementsContentOtherSymptomsSection';

	import {VIEW_MODES} from '../../services/utils';
	import {MEASUREMENT_INPUT_CORRECT_FORM} from '../../services/utils';

	export default {

		name: 'MeasurementsContent',

		components: {
			MeasurementsContentValueList,
			MeasurementsContentHeader,
			MeasurementsContentMedicationSection,
			MeasurementsContentSymptomsSection,
			MeasurementsContentOtherSymptomsSection
		},

		model: {
			event: 'switch-mobile-list'
		},

		computed: {

			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
			}),

			measurementSubmitErrorMessage () {
				return MEASUREMENT_INPUT_CORRECT_FORM;
			},
		},

		methods: {

			addMeasurement () {
				this.$store.dispatch('updateMode', VIEW_MODES.ADD);
			},

			submitMeasurement () {
				this.$refs.medicationSection.validateInput()
					.then(valid => {
						if (valid) {
							this.$refs.symptomsSection.validateInput()
								.then(valid => {
									if (valid) {
										this.$refs.otherSymptomsSection.validateInput()
											.then(valid => {
												if (valid) {
													this.$store.dispatch('addMeasurement');
												} else {
													this.alertSubmitError()
												}
											})
									} else {
										this.alertSubmitError()
									}
								})
						} else {
							this.alertSubmitError()
						}
					})
			},

			editMeasurement () {
				if (this.activeMeasurement.id === null) {
					this.$store.dispatch('updateMode', VIEW_MODES.ADD);
				} else {
					this.$store.dispatch('updateMode', VIEW_MODES.EDIT);
				}
			},

			backToList () {
				this.$emit('switch-mobile-list', true);
			},

			alertSubmitError () {
				alert(this.measurementSubmitErrorMessage);
			}
		},
	}
</script>
<template>
    <div class="app-content-detail">
        <MeasurementsContentHeader/>
        <div id="asthmadiary_measurement_form_wrapper">
            <div id="asthmadiary_measurement_form_section_wrapper">
                <MeasurementsContentMedicationSection ref="medicationSection"/>
                <MeasurementsContentSymptomsSection ref="symptomsSection"/>
                <MeasurementsContentOtherSymptomsSection
                        ref="otherSymptomsSection"/>
            </div>
            <measurements-content-value-list/>
        </div>
    </div>
</template>

<style></style>
