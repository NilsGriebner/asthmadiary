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
	import {mapState, mapGetters} from 'vuex';

	import {VIEW_MODES} from '../../services/utils';
	import {API_DATE_FORMAT} from '../../services/utils';
	import {calculateBackgroundColor} from '../../services/utils';

	import isMobile from 'nextcloud-vue/dist/Mixins/isMobile'

	import MeasurementsListItemBreathlessnessStatusBar
		from "./MeasurementsListItemBreathlessnessStatusBar";

	export default {

		name: 'MeasurementsList',

		components: {
			MeasurementsListItemBreathlessnessStatusBar
		},

		mixins: [
			isMobile
		],

		model: {
			event: 'switch-mobile-list'
		},

		computed: {

			...mapState({
				measurements: state => state.measurements.measurements,
				mode: state => state.measurements.mode,
				dateFormat: state => state.measurements.dateFormat
			}),

			...mapGetters({
				getBestValueOfMeasurement: 'getBestValueOfMeasurement',
				getValueForStatusBar: 'getValueForStatusBar',
				getMedicationPuffsForStatusBar: 'getMedicationPuffsForStatusBar',
			}),
		},

		methods: {

			addMeasurement: function () {
				this.$store.dispatch('updateMode', VIEW_MODES.ADD)
					.then(() => {
						if (isMobile) {
							this.$emit('switch-mobile-list', false);
						}
					});
			},

			selectItem: function (measurement) {
				this.$store.dispatch('setActiveMeasurement', measurement)
					.then(() => {
						this.$store.dispatch('updateMode', VIEW_MODES.SELECT)
							.then(() => {
								if (isMobile) {
									this.$emit('switch-mobile-list', false);
								}
							});
					});
			},

			deleteItem: function (measurement) {
				this.$store.dispatch('deleteMeasurement', measurement);
			},

			colorAvatar (string) {
				return calculateBackgroundColor(string);
			},

			formatDate (measurement) {
				return moment(measurement.date, API_DATE_FORMAT)
					.format(this.dateFormat);
			},

			/*
				TO-DO
				Workaround for item list events, because clicking on
				inner elements triggers multiple functions
		 	*/
			handleListItemClickEvent (event, measurement) {
				if (event.target.className === "icon-add") {
					this.addMeasurement();
				} else if (event.target.className === "icon-delete") {
					this.deleteItem(measurement);
				} else {
					this.selectItem(measurement);
				}
			},
		}
	}
</script>
<template>
	<div class="app-content-list">
		<a class="app-content-list-item"
		   href="#"
		   v-for="measurement in measurements"
		   v-on:click="handleListItemClickEvent($event, measurement)">
			<div class="app-content-list-item-icon"
				 :style="{ 'background-color': colorAvatar(measurement.date) }">
				{{ getBestValueOfMeasurement(measurement.date) }}
			</div>
			<div class="app-content-list-item-line-one">
				<measurements-list-item-breathlessness-status-bar
						v-bind:value="getValueForStatusBar(measurement.date)"
				/>
			</div>
			<div class="app-content-list-item-line-two">
				{{ t('asthmadiary', 'PRN Medication Puffs') }}:
				{{ getMedicationPuffsForStatusBar(measurement.date) }}
			</div>
			<span class="app-content-list-item-details">
				{{ formatDate(measurement) }}
			</span>
			<div class="icon-add"
				 v-if="measurement.id === null">
			</div>
			<div class="icon-delete"
				 v-else>
			</div>
		</a>
	</div>
</template>
