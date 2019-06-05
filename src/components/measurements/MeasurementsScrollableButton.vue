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
	<a class="asthmadiary_navigation_scroll_button"
	   :style="{ 'background-color': buttonStyle }">
		<template v-if="isMobile">
			<div v-bind:class="{ 'icon-checkmark': (mode === edit || mode === add) }"
				 v-on:click.prevent="submitMeasurement">
			</div>
			<div v-bind:class="{ 'icon-rename': mode === select }"
				 v-on:click.prevent="editMeasurement">
			</div>
		</template>
		<template v-else>
			<div v-bind:class="{ 'icon-checkmark-white': (mode === edit || mode === add) }"
				 v-on:click.prevent="submitMeasurement">
			</div>
			<div v-bind:class="{ 'icon-rename-white': mode === select }"
				 v-on:click.prevent="editMeasurement">
			</div>
		</template>
	</a>
</template>

<script>
	import {mapState} from 'vuex';

	import {VIEW_MODES} from '../../services/utils';
	import {calculateBackgroundColor} from '../../services/utils';

	import isMobile from 'nextcloud-vue/dist/Mixins/isMobile';

	export default {
		name: "MeasurementsScrollableButton",

		mixins: [isMobile],

		model: {
			event: 'submit-measuement',
			event: 'edit-measurement'
		},

		computed: {
			...mapState({
				activeMeasurement: state => state.measurements.activeMeasurement,
				mode: state => state.measurements.mode,
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

			buttonStyle () {
				if (this._isMobile()) {
					return 'transparent';
				}
				return this.buttonColor();
			},
		},

		methods: {
			submitMeasurement () {
				this.$emit('submit-measurement');
			},

			editMeasurement () {
				this.$emit('edit-measurement');
			},

			buttonColor () {
				return calculateBackgroundColor(this.activeMeasurement.date);
			},
		}
	}
</script>

<style></style>