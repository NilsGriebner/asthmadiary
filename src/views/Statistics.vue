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
	<Content app-name="asthmadiary">
		<navigation/>
		<AppContent>
			<div id="app-content-wrapper">
				<statistics-content v-if="!loading"/>
			</div>
		</AppContent>
	</Content>
</template>

<script>
	import {Content, AppContent} from 'nextcloud-vue';
	import Navigation from "../components/navigation/Navigation";
	import StatisticsContent from "../components/statistics/StatisticsContent";

	export default {

		name: "Statistics",

		components: {
			Navigation,
			Content,
			AppContent,
			StatisticsContent
		},

		data () {
			return {
				loading: true,
			}
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

<style></style>