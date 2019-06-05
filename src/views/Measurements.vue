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
	import MeasurementsContent
		from '../components/measurements/MeasurementsContent';
	import MeasurementsList from '../components/measurements/MeasurementsList';
	import Navigation from '../components/navigation/Navigation';
	import MeasurementsScrollableButton
		from "../components/measurements/MeasurementsScrollableButton";
	import MeasurementsScrollableButtonMobileBackToList
		from "../components/measurements/MeasurementsScrollableButtonMobileBackToList";

	import {Content, AppContent} from 'nextcloud-vue';
	import isMobile from 'nextcloud-vue/dist/Mixins/isMobile';

	export default {

		name: 'Measurements',

		components: {
			MeasurementsScrollableButton,
			MeasurementsScrollableButtonMobileBackToList,
			Navigation,
			MeasurementsContent,
			MeasurementsList,
			AppContent,
			Content
		},

		mixins: [
			isMobile
		],

		data () {
			return {
				loading: true,
				mobileListActive: this._isMobile()
			}
		},

		methods: {
			submitMeasurement () {
				this.$refs.measurementsContent.submitMeasurement();
			},

			editMeasurement () {
				this.$refs.measurementsContent.editMeasurement();
			},

			switchMobileList (active) {
				this.mobileListActive = active;
			},
		},

		beforeMount () {
			this.$store.dispatch('getValues')
				.then(() => {
					this.$store.dispatch('getMeasurements')
						.then(() => {
							this.$store.dispatch('setFirstMeasurementActive')
								.then(() => {
									this.$store.dispatch('setInitialDateFormat')
										.then(() => {
											this.loading = false;
										});
								});
						})
				});
		}
	}
</script>

<template>
	<Content app-name="asthmadirary">
		<navigation/>
		<AppContent>
			<MeasurementsScrollableButton
					v-if="!mobileListActive && !loading"
					@submit-measurement="submitMeasurement()"
					@edit-measurement="editMeasurement()"
			/>
			<MeasurementsScrollableButtonMobileBackToList
					v-if="isMobile && !mobileListActive"
					@switch-mobile-list="(active) => {switchMobileList(active);}"
			/>
			<div id="app-content-wrapper">
				<measurements-list
						v-if="!isMobile || (isMobile && mobileListActive)"
						@switch-mobile-list="(active) => {switchMobileList(active);}"
				/>
				<measurements-content
						ref="measurementsContent"
						v-if="(!loading && !isMobile) || (isMobile && !mobileListActive)"
				/>
			</div>
		</AppContent>
	</Content>
</template>
