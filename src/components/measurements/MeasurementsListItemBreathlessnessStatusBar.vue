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
	<div>
		<div style="padding-left: 0px !important;"
			 class="app-content-list-item-line-two">
			{{ t('asthmadiary', "Breathlessness") }}:
		</div>
		<progress v-bind:class="{ warn:isWarn }"
				  :value="value"
				  max="100">
		</progress>
	</div>
</template>

<script>
	export default {

		name: "MeasurementsListItemBreathlessnessStatusBar",

		props: {
			value: {
				type: Number,
				required: true
			}
		},
		computed: {
			isWarn: function () {
				return this.value >= 75;
			},
		}
	}
</script>
