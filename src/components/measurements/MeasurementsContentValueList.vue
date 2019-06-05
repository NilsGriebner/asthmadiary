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

<template>
	<div id="asthmadiary_measurment_content_value_wrapper">
		<div class="asthmadiary_flex_headline">
			<div class="icon-category-monitoring"></div>
			<h3>{{ t('asthmadiary','Peak-Flow Values') }}:</h3>
		</div>
		<form>
			<div class="asthmadiary_measurement_form_flex_column">
				<div id="asthmadiary_measurement_content_value_input_wrapper">
					<div class="asthmadiary_measurement_form_flex_column">
						<label for="asthmadiary_measurement_content_value_time_input">
							{{ t('asthmadiary', 'Value') }}
						</label>
						<input id="asthmadiary_measurement_content_value_time_input"
							   name="time"
							   :placeholder=" t('asthmadiary', 'Peak-Flow value')"
							   :disabled="mode === select"
							   v-model="value"
							   v-validate="'max_value:900|min_value:1|required'"/>
					</div>
					<div class="asthmadiary_measurement_form_flex_column">
						<label for="asthmadiary_measurement_content_value_value_input">
							{{ t('asthmadiary', 'Daytime') }}
						</label>
						<input id="asthmadiary_measurement_content_value_value_input"
							   name="value"
							   :placeholder="timeInputPlaceholder"
							   :disabled="mode === select"
							   v-model="time"
							   v-validate="'date_format:HH:mm:ss|required'"/>
					</div>
				</div>
				<span class="asthmadiary_error_message_text" v-if="edit || add">
					{{ errors.first('time') }}
					{{ errors.first('value') }}
				</span>
			</div>
			<button type="button" v-on:click.prevent="submitValue"
					:hidden="mode === select">
				{{ t('asthmadiary', 'Add') }}
			</button>
		</form>
		<span class="asthmadiary_error_message_text" v-if="submitError">
			{{ submitErrorMessage }}
		</span>
		<vue-good-table :columns="columns"
						:rows="rows"
						:sort-options="{
							enabled: true,
							initialSortBy: {field: 'time', type: 'asc'}
						}"
						:pagination-options="{
							enabled: true,
							mode: 'records',
							perPage: 5,
							position: 'top',
							perPageDropdown: [5, 10, 20],
							dropdownAllowAll: false,
							nextLabel: pageNextText,
							prevLabel: pagePrevText,
							rowsPerPageLabel: pageRowsPerPageText,
							ofLabel: pagePageOf,
							pageLabel: pagePageText,
							allLabel: 'All',
  						}"
						:selectOptions="{
    						enabled: selectEnabled,
							selectOnCheckboxOnly: true,
							selectionText: selectSelectionText,
							clearSelectionText: selectClearText,
  						}"
						@on-selected-rows-change="selectionChanged">
			<div slot="selected-row-actions">
				<button
						v-on:click.prevent="deleteValue">
					{{ t('asthmadiary', "Delete selected") }}
				</button>
			</div>
		</vue-good-table>
	</div>
</template>

<script>
	import {mapState, mapGetters} from 'vuex';
	import {VueGoodTable} from 'vue-good-table';
	import 'vue-good-table/dist/vue-good-table.css'

	import {
		VIEW_MODES,
		API_DATE_FORMAT,
		SHOW_DATE_FORMAT_DE
	} from '../../services/utils';
	import {MEASUREMENT_INPUT_CORRECT_FORM} from '../../services/utils';
	import {MEASUREMENT_VALUE_INPUT_DOUBLE_ERROR} from '../../services/utils';
	import {MEASUREMENT_VALUE_SUBMIT_MEASUREMENT_FIRST_ERROR} from '../../services/utils';

	export default {

		name: "MeasurementsContentValueList",

		data () {
			return {
				columns: [
					{
						label: t('asthmadiary', 'Date'),
						field: 'date'
					},
					{
						label: t('asthmadiary', 'Time'),
						field: 'time'
					},
					{
						label: t('asthmadiary', 'Value'),
						field: 'value'
					}
				],
				rows: null,
				value: null,
				time: null,
				submitError: null,
				submitErrorMessage: null,
				selectedRows: null,
			}
		},

		components: {
			VueGoodTable
		},

		computed: {
			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
				mode: state => state.measurements.mode,
				currentLocale: state => state.measurements.currentLocale,
				dateFormat: state => state.measurements.dateFormat,
			}),

			...mapGetters([
				'getActiveMeasurement'
			]),

			select () {
				return VIEW_MODES.SELECT;
			},

			add () {
				return VIEW_MODES.ADD;
			},

			edit () {
				return VIEW_MODES.EDIT;
			},

			selectEnabled () {
				return this.mode === VIEW_MODES.EDIT;
			},

			submitErrorMessageCorrect () {
				return MEASUREMENT_INPUT_CORRECT_FORM;
			},

			submitErrorMessageDouble () {
				return MEASUREMENT_VALUE_INPUT_DOUBLE_ERROR;
			},

			submitErrorMessageMeasurementFirst () {
				return MEASUREMENT_VALUE_SUBMIT_MEASUREMENT_FIRST_ERROR;
			},

			timeInputPlaceholder () {
				return "HH:MM:SS";
			},

			selectClearText () {
				return t('asthmadiary', "clear");
			},

			selectSelectionText () {
				return t('asthmadiary', "rows selected");
			},

			pageText () {
				return this.pagePageText;
			},

			pagePageText () {
				return t('asthmadiary', "Page");
			},

			pagePageOf () {
				return t('asthmadiary', "of");
			},

			pageRowsPerPageText () {
				return t('asthmadiary', "Rows per page");
			},

			pagePrevText () {
				return t('asthmadiary', "Prev");
			},

			pageNextText () {
				return t('asthmadiary', "Next");
			},
		},

		watch: {
			dateFormat () {
				this.calculateRows()
			},

			activeMeasurement: {
				handler: function () {
					this.calculateRows();
				},
				deep: true
			}
		},

		methods: {
			submitValue () {
				this.$validator.validate().then(valid => {
					/**
					 * Dont accept values with existing time
					 */
					let result =
						this.activeMeasurement
							.values
							.filter(v => v.time === this.time);

					if (result.length > 0) {
						this.submitError = true;
						this.submitErrorMessage = this.submitErrorMessageDouble;
						return;
					}

					/**
					 * User needs to create a measurement first
					 */
					if (this.activeMeasurement.id === null) {
						this.submitError = true;
						this.submitErrorMessage = this.submitErrorMessageMeasurementFirst;
						return;
					}

					if (!valid) {
						this.submitError = true;
						this.submitErrorMessage = this.submitErrorMessageCorrect;

					} else {
						this.submitError = false;
						this.$store.dispatch('addValue', this.createValue());
					}
				})
			},

			createValue () {
				let value = {};

				value.time = this.time;
				value.value = this.value;
				value.date = this.activeMeasurement.date;

				return value;
			},

			selectionChanged (selection) {
				this.selectedRows = selection;
			},

			deleteValue () {
				for (let value of this.selectedRows.selectedRows) {
					this.$store.dispatch('deleteValue', value);
				}
			},

			calculateRows () {
				let result = [];
				for (let value of this.getActiveMeasurement.values) {
					/**
					 * Destroy reference to state object
					 */
					let newValue = JSON.parse(JSON.stringify(value));
					newValue.date = moment(newValue.date, API_DATE_FORMAT).format(this.dateFormat);
					result.push(newValue);
				}
				this.rows = result;
			}
		},

		beforeMount () {
			this.calculateRows();
		}
	}
</script>
<style></style>
